<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'role:admin|seller']);
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $Product = Product::all();
      return response()->json($Product, 200);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error retrieving categories'], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
        'image' => 'required|string',
        'quantity' => 'required|integer',
        'category_id' => [
          'required',
          'integer',
          Rule::exists('categories', 'id'),
        ],
        'status' => 'required|string',
        'description' => 'required|string',
      ]);

      $Product = Product::create($validatedData);
      return response()->json($Product, 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json(['message' => $e->getMessage()], 422);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error creating Product'], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      $Product = Product::findOrFail($id);
      return response()->json($Product, 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'Product not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error retrieving Product'], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    try {
      $validatedData = $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
        'image' => 'required|string',
        'quantity' => 'required|integer',
        'category_id' => [
          'required',
          'integer',
          Rule::exists('categories', 'id'),
        ],
        'status' => 'required|string',
        'description' => 'required|string',
      ]);

      $product = Product::findOrFail($id);
      $product->update($validatedData);

      return response()->json($product, 200);
    } catch (ModelNotFoundException $e) {
      return response()->json(['message' => 'Product not found'], 404);
    } catch (ValidationException $e) {
      return response()->json(['message' => $e->getMessage()], 422);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error updating product'], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    try {
      $product = Product::findOrFail($id);
      $product->delete();

      return response()->json(['message' => 'Product deleted'], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'Product not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error deleting product'], 500);
    }
  }


  public function getByCategory($categoryName)
  {
    try {
      $category = Categories::where('name', $categoryName)->firstOrFail();

      $products = $category->products->map(function ($product) {
        return $product->only(['id', 'name', 'price', 'image', 'quantity', 'status', 'description', 'created_at', 'updated_at']);
      });

      $response = [
        'products' => $products->toArray(),
        'category' => $category->only(['id', 'name', 'description', 'created_at', 'updated_at']),
      ];

      return response()->json($response, 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'Category not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error retrieving products'], 500);
    }
  }
}
