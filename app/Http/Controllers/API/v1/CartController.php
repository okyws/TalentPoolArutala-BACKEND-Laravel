<?php

namespace App\Http\Controllers\API\v1;

use App\Models\AddToCart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\Controller;
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
        'cart_items' => $cartItems,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
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
          'message' => 'Product not found.',
        ], 404);
      }

      $quantity = $request->input('quantity');
      $total_price = $quantity * $product->price;

      $cartItem = new AddToCart();
      $cartItem->user_id = $user_id;
      $cartItem->product_id = $product->id;
      $cartItem->quantity = $quantity;
      $cartItem->total_price = $total_price;
      $cartItem->save();

      return response()->json([
        'message' => 'Cart item created successfully.',
        'cart_item' => $cartItem,
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
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
          'message' => 'Cart item not found.',
        ], 404);
      }

      return response()->json([
        'cart_item' => $cartItem,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
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
          'message' => 'Cart item not found.',
        ], 404);
      }

      $product = Product::find($request->input('product_id'));

      if (!$product) {
        return response()->json([
          'message' => 'Product not found.',
        ], 404);
      }

      $cartItem->product_id = $product->id;
      $cartItem->quantity = $request->input('quantity');
      $cartItem->total_price = $product->price * $cartItem->quantity;
      $cartItem->save();

      return response()->json([
        'message' => 'Cart item updated successfully.',
        'cart_item' => $cartItem,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
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
          'message' => 'Cart item not found.',
        ], 404);
      }

      $cartItem->delete();

      return response()->json([
        'message' => 'Cart item deleted successfully.',
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'An error occurred while deleting the cart item.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
