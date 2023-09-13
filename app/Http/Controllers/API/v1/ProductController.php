<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\API\v1\Controller;

class ProductController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'role:admin|seller'], ['except' => ['index', 'show', 'getByCategory']]);
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $Product = Product::all();
      return response()->json([
        'status' => 200,
        'message' => 'All Products retrieved successfully',
        'data' => $Product
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error retrieving categories'
      ], 500);
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
        'categories_id' => [
          'required',
          'integer',
          Rule::exists('categories', 'id'),
        ],
        'description' => 'required|string',
      ]);

      $validatedData['status'] = $validatedData['quantity'] == 0 ? 'out of stock' : 'in stock';

      $Product = Product::create($validatedData);
      return response()->json([
        'status' => 201,
        'message' => 'Product created successfully',
        'data' => $Product,
      ], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'status' => 422,
        'message' => $e->getMessage()
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error creating Product'
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      $Product = Product::findOrFail($id);
      return response()->json([
        'status' => 200,
        'message' => "Product with ID: $id retrieved successfully",
        'data' => $Product,
      ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => "Product with ID: $id not found"
      ], 404);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error retrieving Product'
      ], 500);
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
        'categories_id' => [
          'required',
          'integer',
          Rule::exists('categories', 'id'),
        ],
        'description' => 'required|string',
      ]);

      $product = Product::findOrFail($id);
      $stock = $validatedData['quantity'];
      $product->status = $stock == 0 ? 'out of stock' : 'in stock';

      $product->update($validatedData);

      return response()->json([
        'status' => 200,
        'message' => "Product with ID: $id updated successfully",
        'data' => $product
      ], 200);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => "Product with ID: $id not found"
      ], 404);
    } catch (ValidationException $e) {
      return response()->json([
        'status' => 422,
        'message' => $e->getMessage()
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error updating product'
      ], 500);
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

      return response()->json([
        'status' => 200,
        'message' => "Product with ID: $id deleted"
      ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => "Product with ID: $id not found"
      ], 404);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error deleting product'
      ], 500);
    }
  }

  public function getByCategory($categoryName)
  {
    try {
      $category = Categories::where('name', $categoryName)->firstOrFail();

      $products = Product::with('categories')->where('categories_id', $category->id)->get();

      $response = [
        'products' => $products,
        'categories' => $category->only(['id', 'name', 'description']),
      ];

      return response()->json([
        'status' => 200,
        'data' => $response
      ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => "Category with name: $categoryName, not found"
      ], 404);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error retrieving products'
      ], 500);
    }
  }
}
