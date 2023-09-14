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
}
