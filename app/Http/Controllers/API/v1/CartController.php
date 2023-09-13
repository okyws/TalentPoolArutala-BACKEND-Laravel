<?php

namespace App\Http\Controllers\API\v1;

use App\Models\AddToCart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
      $user_id = Auth::id();
      $cartItems = AddToCart::where('user_id', $user_id)->get();

      return response()->json([
        'status' => 200,
        'cart_items' => $cartItems,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'An error occurred while retrieving cart items.',
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
      $user_id = Auth::id();
      $product = Product::find($request->input('product_id'));

      if (!$product) {
        return response()->json([
          'status' => 404,
          'message' => 'Product not found.',
        ], 404);
      }

      $quantity = $request->input('quantity');
      $subtotal = $quantity * $product->price;

      $cartItem = new AddToCart();
      $cartItem->user_id = $user_id;
      $cartItem->product_id = $product->id;
      $cartItem->quantity = $quantity;
      $cartItem->subtotal = $subtotal;
      $cartItem->status = 'cart';
      $cartItem->save();

      return response()->json([
        'status' => 201,
        'message' => 'Cart item created successfully.',
        'cart_item' => $cartItem,
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'An error occurred while creating the cart item.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    try {
      $user_id = Auth::id();

      $cartItem = AddToCart::where('user_id', $user_id)->find($id);

      if (!$cartItem) {
        return response()->json([
          'status' => 404,
          'message' => 'Cart item not found.',
        ], 404);
      }

      return response()->json([
        'status' => 200,
        'message' => 'Cart item retrieved successfully.',
        'cart_item' => $cartItem,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'An error occurred while retrieving the cart item.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    try {
      $user_id = Auth::id();
      $cartItem = AddToCart::where('user_id', $user_id)->find($id);

      if (!$cartItem) {
        return response()->json([
          'status' => 404,
          'message' => 'Cart item not found.',
        ], 404);
      }

      $product = Product::find($request->input('product_id'));

      if (!$product) {
        return response()->json([
          'status' => 404,
          'message' => 'Product not found.',
        ], 404);
      }

      if ($cartItem->status == 'cart') {
        $cartItem->product_id = $product->id;
        $cartItem->quantity = $request->input('quantity');
        $cartItem->subtotal = $product->price * $cartItem->quantity;
        $cartItem->status = 'cart';
        $cartItem->save();

        return response()->json([
          'status' => 200,
          'message' => 'Cart item updated successfully.',
          'cart_item' => $cartItem,
        ], 200);
      } elseif ($cartItem->status == 'checkout') {
        return response()->json([
          'status' => 422,
          'message' => 'Your order is in checkout status. Please complete the payment.',
          'cart_item' => $cartItem,
        ], 422);
      } elseif ($cartItem->status == 'cancelled') {
        return response()->json([
          'status' => 422,
          'message' => 'Your order has been cancelled.',
          'cart_item' => $cartItem,
        ], 422);
      } else {
        return response()->json([
          'status' => 422,
          'message' => 'Invalid Request',
        ], 400);
      }
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'An error occurred while updating the cart item.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    try {
      $user_id = Auth::id();
      $cartItem = AddToCart::where('user_id', $user_id)->find($id);

      if (!$cartItem) {
        return response()->json([
          'status' => 404,
          'message' => 'Cart item not found.',
        ], 404);
      }

      $cartItem->delete();

      return response()->json([
        'status' => 200,
        'message' => 'Cart item deleted successfully.',
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'An error occurred while deleting the cart item.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Simulation Cancel the specified resource from storage.
   */
  public function cancel(string $id)
  {
    try {
      $user_id = Auth::id();
      $cartItem = AddToCart::where('user_id', $user_id)->find($id);

      if (!$cartItem) {
        return response()->json([
          'status' => 404,
          'message' => 'Cart item not found.',
        ], 404);
      }

      if ($cartItem->status == 'cart' || $cartItem->status == 'checkout') {
        $cartItem->status = 'cancelled';
        $cartItem->save();

        return response()->json([
          'status' => 200,
          'message' => 'Cart item cancelled successfully.',
          'cart_item' => $cartItem,
        ], 200);
      } elseif ($cartItem->status == 'cancelled') {
        return response()->json([
          'status' => 422,
          'message' => 'Cart item already cancelled before.',
          'current_status' => $cartItem->status,
          'status' => 442,
        ], 422);
      } else {
        return response()->json([
          'status' => 422,
          'message' => 'Unable to cancel cart item. Invalid status.',
          'cart_item' => $cartItem,
        ], 400);
      }
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'An error occurred while cancelling the cart item.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Simulation Checkout the specified resource from storage.
   */
  public function checkout(string $id)
  {
    try {
      $user_id = Auth::id();
      $cartItem = AddToCart::where('user_id', $user_id)->find($id);

      if (!$cartItem) {
        return response()->json([
          'status' => 404,
          'message' => 'Cart item not found.',
        ], 404);
      }

      if ($cartItem->status == 'cart') {
        $cartItem->status = 'checkout';
        $cartItem->save();

        return response()->json([
          'status' => 200,
          'message' => 'Cart item checked out successfully.',
          'cart_item' => $cartItem,
        ], 200);
      } elseif ($cartItem->status == 'checkout') {
        return response()->json([
          'status' => 422,
          'message' => 'Cart item already checked out. Please complete the payment.',
          'current_status' => $cartItem->status,
        ], 422);
      } else {
        return response()->json([
          'status' => 422,
          'message' => 'Unable to check out order. Invalid status.',
          'cart_item' => $cartItem,
        ], 404);
      }
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'An error occurred while checking out Cart item.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
