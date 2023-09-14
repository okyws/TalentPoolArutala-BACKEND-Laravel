<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'role:customer|seller'], ['except' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $userId = Auth::id();

      if (!$userId) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $orders = Order::where('user_id', $userId)
        ->with('cart')->get();

      $response = [
        'status' => 200,
        'message' => 'Orders retrieved successfully',
        'count' => $orders->count(),
        'user' => [
          'id' => Auth::user()->id,
          'name' => Auth::user()->name,
          'email' => Auth::user()->email,
        ],
        'orders' => $orders,
      ];

      return response()->json(
        $response,
        200
      );
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'An error occurred while retrieving orders',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, Cart $cart, $id)
  {
    try {
      $userId = Auth::id();

      if (!$userId) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $cart = $cart->where('user_id', $userId)->findOrFail($id);
      $totalItem = CartItem::where('cart_id', $id)->sum('quantity');
      $totalPayment = CartItem::where('cart_id', $id)->sum('subtotal');

      $validatedData = $request->validate([
        'address' => 'required|string',
        'courier_service' => 'required|string|in:jne,tiki,pos,jnt,sicepat',
        'payment_method' => 'required|string|in:cod,credit,debit,e-money,transfer',
      ]);

      $deliveryCost = 0;
      $courierService = $validatedData['courier_service'];
      switch ($courierService) {
        case 'jne':
          $deliveryCost = 10000;
          break;
        case 'tiki':
          $deliveryCost = 13000;
          break;
        case 'pos':
          $deliveryCost = 15000;
          break;
        case 'jnt':
          $deliveryCost = 9000;
          break;
        case 'sicepat':
          $deliveryCost = 11000;
          break;
        default:
          $deliveryCost = 10000;
          break;
      }

      $validatedData['delivery_cost'] = $deliveryCost;
      $validatedData['total_item'] = $totalItem;
      $validatedData['total_payment'] = $totalPayment + $deliveryCost;
      $validatedData['status'] = 'waiting for payment';

      $order = Order::create(['user_id' => $userId, 'cart_id' => $cart->id] + $validatedData);

      foreach ($cart->cartItems as $cartItem) {
        $product = Product::findOrFail($cartItem->product_id);

        if ($product->quantity < $cartItem->quantity) {
          throw new \Exception(' Insufficient quantity for product ' . $product->name);
        }
        $product->quantity -= $cartItem->quantity;
        $product->save();

        OrderDetail::create([
          'order_id' => $order->id,
          'product_id' => $cartItem->product_id,
          'quantity' => $cartItem->quantity,
          'subtotal' => $cartItem->subtotal,
        ]);
      }

      $cart->delete();

      return response()->json([
        'status' => 201,
        'data' => $order
      ], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'status' => 422,
        'message' => $e->getMessage()
      ], 422);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => 'Cart not found'
      ], 404);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error creating order' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    try {
      $userId = Auth::id();

      if (!$userId) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $order = Order::where('id', $id)
        ->where('user_id', $userId)
        ->with('orderDetails')
        ->first();

      if (!$order) {
        return response()->json([
          'status' => 404,
          'message' => 'Order not found',
        ], 404);
      }

      return response()->json([
        'status' => 200,
        'data' => $order,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error retrieving order, ' . $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    try {
      $userId = Auth::id();

      if (!$userId) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $order = Order::where('id', $id)->where('user_id', $userId)->first();

      if (!$order) {
        return response()->json([
          'status' => 404,
          'message' => 'Order not found',
        ], 404);
      }

      $status = $order->status;

      switch ($status) {
        case 'canceled':
        case 'shipped':
        case 'delivered':
          return response()->json([
            'status' => 400,
            'message' => 'Could not update order when status is ' . $status,
          ], 400);
        case 'pending':
        case 'paid':
          $validator = Validator::make($request->all(), [
            'address' => 'required',
            'courier_service' => 'required',
            'payment_method' => 'required',
          ]);

          if ($validator->fails()) {
            return response()->json([
              'status' => 422,
              'message' => 'Validation Error',
              'errors' => $validator->errors(),
            ], 422);
          }

          $order->address = $request->input('address');
          $order->courier_service = $request->input('courier_service');
          $order->payment_method = $request->input('payment_method');
          $order->status = $status;
          $order->save();
          break;
        case 'process':
          return response()->json([
            'status' => 400,
            'message' => 'Order is already in process, could not update order',
          ], 400);
        default:
          return response()->json([
            'status' => 400,
            'message' => 'Could not update order',
          ], 400);
          break;
      }

      return response()->json([
        'status' => 200,
        'message' => 'Order updated successfully',
        'data' => $order,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error updating order' . $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    try {
      $user = Auth::user();

      // jika ada pesan error dari intelephense abaikan saja karena sebenarnya tidak error
      // dalam method hasRole, ini mungkin bug dari intelephense
      if ($user->hasRole('admin')) {
        $order = Order::find($id);

        if (!$order) {
          return response()->json([
            'status' => 404,
            'message' => 'Order not found',
          ], 404);
        }

        $order->delete();

        return response()->json([
          'status' => 200,
          'message' => 'Order deleted successfully',
        ], 200);
      }

      // jika ingin mengizinkan user menghapus order sendiri
      // fitur ini non aktifkan, jadi hanya admin yang bisa

      // $userId = Auth::id();

      // if (!$userId) {
      //   return response()->json([
      //     'status' => 401,
      //     'message' => 'Unauthorized',
      //   ], 401);
      // }

      // $order = Order::where('id', $id)->where('user_id', $userId)->first();

      // if (!$order) {
      //   return response()->json([
      //     'status' => 404,
      //     'message' => 'Order not found',
      //   ], 404);
      // }

      // $order->delete();

      // return response()->json([
      //   'status' => 200,
      //   'message' => 'Order deleted successfully',
      // ], 200);
    } catch (AuthorizationException $e) {
      return response()->json([
        'status' => 403,
        'message' => 'Forbidden',
      ], 403);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error deleting order',
      ], 500);
    }
  }

  public function updateStatus($status, $orderId)
  {
    try {
      $user = Auth::user();

      if (!$user) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $order = Order::where('user_id', $user->id)
        ->where('id', $orderId)
        ->first();

      if (!$order) {
        return response()->json([
          'status' => 404,
          'message' => 'Order not found',
        ], 404);
      }

      $validStatuses = ['canceled', 'shipped', 'delivered', 'pending', 'paid'];

      if (!in_array($status, $validStatuses)) {
        return response()->json([
          'status' => 400,
          'message' => 'Invalid status provided, only ' . implode(', ', $validStatuses) . ' are allowed',
        ], 400);
      }

      $order->status = $status;
      $order->save();

      return response()->json([
        'status' => 200,
        'message' => 'Order status updated successfully',
        'data' => $order,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error updating order status',
      ], 500);
    }
  }
}
