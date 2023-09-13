<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'role:admin|seller'], ['except' => ['index', 'show', 'getProductsByCategory']]);
  }

  public function index()
  {
    try {
      $categories = Categories::all();
      return response()->json([
        'status' => 200,
        'categories' => $categories,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error retrieving categories'
      ], 500);
    }
  }

  public function store(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'name' => 'required|string|unique:categories,name',
        'description' => 'required|string',
      ]);

      $category = Categories::create($validatedData);
      return response()->json([
        'status' => 201,
        'data' => $category
      ], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'status' => 422,
        'message' => $e->getMessage()
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error creating category'
      ], 500);
    }
  }

  public function show($id)
  {
    try {
      $category = Categories::findOrFail($id);
      return response()->json([
        'status' => 200,
        'data' => $category
      ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => "Category with $id, not found"
      ], 404);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error retrieving category'
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $validatedData = $request->validate([
        'name' => 'required|string|unique:categories,name',
        'description' => 'required|string',
      ]);

      $category = Categories::findOrFail($id);
      $category->update($validatedData);

      return response()->json([
        'status' => 200,
        'data' => $category
      ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => 'Category not found'
      ], 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'status' => 422,
        'message' => $e->getMessage()
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error updating category'
      ], 500);
    }
  }

  public function destroy($id)
  {
    try {
      $category = Categories::findOrFail($id);
      $category->delete();

      return response()->json([
        'status' => 200,
        'message' => 'Category deleted'
      ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'status' => 404,
        'message' => 'Category not found'
      ], 404);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error deleting category'
      ], 500);
    }
  }

  public function getProductsByCategory($categoryName)
  {
    try {
      $category = Categories::where('name', $categoryName)->firstOrFail();
      $category_id = $category->id;
      $category_2 = Categories::with('products')->where('id', $category_id)->firstOrFail();

      return response()->json([
        'status' => 200,
        'data' => $category_2
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
