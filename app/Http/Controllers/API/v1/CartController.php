<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'role:customer|seller']);
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

      /**
       * Cart Only with this syntax
       * $carts = Cart::where('user_id', $userId)->get();
       */

      // all cart with items
      $cart = Cart::where('user_id', $userId)
        ->with('cartItems.product')
        ->get();

      return response()->json([
        'status' => 200,
        'carts' => $cart,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'An error occurred while retrieving carts.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      $userId = Auth::id();

      if (!$userId) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $cart = Cart::create([
        'user_id' => $userId,
      ]);

      $items = $request->input('items');

      if (empty($items)) {
        return response()->json([
          'status' => 400,
          'message' => 'No items provided.',
        ], 400);
      }

      foreach ($items as $item) {
        $validator = Validator::make($item, [
          'product_id' => 'required|integer',
          'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
          return response()->json([
            'status' => 400,
            'message' => 'Validation error.',
            'errors' => $validator->errors(),
          ], 400);
        }

        $cart->addItem($item['product_id'], $item['quantity']);
      }

      return response()->json([
        'status' => 201,
        'message' => 'Items added to cart successfully.',
        'data' => $cart,
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Failed to add items to cart.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      $userId = Auth::id();

      if (!$userId) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $cart = Cart::where('user_id', $userId)
        ->with('cartItems.product')
        ->findOrFail($id);

      return response()->json([
        'status' => 200,
        'data' => $cart,
      ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => 'Cart not found.',
      ], 404);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Failed to show cart.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    try {
      $userId = Auth::id();

      if (!$userId) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $cart = Cart::where('user_id', $userId)
        ->where('id', $id)
        ->first();

      if (!$cart) {
        return response()->json([
          'status' => 404,
          'message' => 'Cart not found.',
        ], 404);
      }

      $items = $request->input('items');

      if (empty($items)) {
        return response()->json([
          'status' => 400,
          'message' => 'No items provided.',
        ], 400);
      }

      foreach ($items as $item) {
        $validator = Validator::make($item, [
          'product_id' => 'required|integer',
          'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
          return response()->json([
            'status' => 400,
            'message' => 'Validation error.',
            'errors' => $validator->errors(),
          ], 400);
        }

        // Update the item if it already exists in the cart
        $existingCartItem = $cart->cartItems()->where('product_id', $item['product_id'])->first();

        if ($existingCartItem) {
          if ($item['quantity'] === 0 || $item['quantity'] === null || $item['quantity'] === '') {
            $existingCartItem->delete();

            return response()->json([
              'status' => 200,
              'message' => 'Item removed from cart.',
            ], 200);
          } else {
            $existingCartItem->quantity = $item['quantity'];
            $existingCartItem->subtotal = $item['quantity'] * $existingCartItem->product->price;
            $existingCartItem->save();
          }
        } else {
          return response()->json([
            'status' => 404,
            'message' => 'Item not found in the cart.',
          ], 404);
        }
      }

      return response()->json([
        'status' => 201,
        'message' => 'Items updated in the cart successfully.',
        'data' => $items,
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Failed to update cart.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    try {
      $userId = Auth::id();

      if (!$userId) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $cart = Cart::where('user_id', $userId)
        ->findOrFail($id);

      $cart->delete();

      return response()->json([
        'status' => 200,
        'message' => 'Cart deleted successfully.',
      ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => 'Cart not found.',
      ], 404);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Failed to delete cart.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Simulation Cancel the specified resource from storage.
   */
  // public function cancel(string $id)
  // {
  //   try {
  //     $user_id = Auth::id();
  //     $cartItem = AddToCart::where('user_id', $user_id)->find($id);

  //     if (!$cartItem) {
  //       return response()->json([
  //         'status' => 404,
  //         'message' => 'Cart item not found.',
  //       ], 404);
  //     }

  //     if ($cartItem->status == 'cart' || $cartItem->status == 'checkout') {
  //       $cartItem->status = 'cancelled';
  //       $cartItem->save();

  //       return response()->json([
  //         'status' => 200,
  //         'message' => 'Cart item cancelled successfully.',
  //         'cart_item' => $cartItem,
  //       ], 200);
  //     } elseif ($cartItem->status == 'cancelled') {
  //       return response()->json([
  //         'status' => 422,
  //         'message' => 'Cart item already cancelled before.',
  //         'current_status' => $cartItem->status,
  //         'status' => 442,
  //       ], 422);
  //     } else {
  //       return response()->json([
  //         'status' => 422,
  //         'message' => 'Unable to cancel cart item. Invalid status.',
  //         'cart_item' => $cartItem,
  //       ], 400);
  //     }
  //   } catch (\Exception $e) {
  //     return response()->json([
  //       'status' => 500,
  //       'message' => 'An error occurred while cancelling the cart item.',
  //       'error' => $e->getMessage(),
  //     ], 500);
  //   }
  // }

  /**
   * Simulation Checkout the specified resource from storage.
   */
  // public function checkout(string $id)
  // {
  //   try {
  //     $user_id = Auth::id();
  //     $cartItem = AddToCart::where('user_id', $user_id)->find($id);

  //     if (!$cartItem) {
  //       return response()->json([
  //         'status' => 404,
  //         'message' => 'Cart item not found.',
  //       ], 404);
  //     }

  //     if ($cartItem->status == 'cart') {
  //       $cartItem->status = 'checkout';
  //       $cartItem->save();

  //       return response()->json([
  //         'status' => 200,
  //         'message' => 'Cart item checked out successfully.',
  //         'cart_item' => $cartItem,
  //       ], 200);
  //     } elseif ($cartItem->status == 'checkout') {
  //       return response()->json([
  //         'status' => 422,
  //         'message' => 'Cart item already checked out. Please complete the payment.',
  //         'current_status' => $cartItem->status,
  //       ], 422);
  //     } else {
  //       return response()->json([
  //         'status' => 422,
  //         'message' => 'Unable to check out order. Invalid status.',
  //         'cart_item' => $cartItem,
  //       ], 404);
  //     }
  //   } catch (\Exception $e) {
  //     return response()->json([
  //       'status' => 500,
  //       'message' => 'An error occurred while checking out Cart item.',
  //       'error' => $e->getMessage(),
  //     ], 500);
  //   }
  // }
}
