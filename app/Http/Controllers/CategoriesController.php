<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'role:admin|seller']);
  }

  public function index()
  {
    try {
      $categories = Categories::all();
      return response()->json($categories, 200);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error retrieving categories'], 500);
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
      return response()->json($category, 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json(['message' => $e->getMessage()], 422);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error creating category'], 500);
    }
  }

  public function show($id)
  {
    try {
      $category = Categories::findOrFail($id);
      return response()->json($category, 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'Category not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error retrieving category'], 500);
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

      return response()->json($category, 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'Category not found'], 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json(['message' => $e->getMessage()], 422);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error updating category'], 500);
    }
  }

  public function destroy($id)
  {
    try {
      $category = Categories::findOrFail($id);
      $category->delete();

      return response()->json(['message' => 'Category deleted'], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'Category not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error deleting category'], 500);
    }
  }

  public function getProductsByCategory($categoryName)
  {
    try {
      $category = Categories::where('name', $categoryName)->firstOrFail();

      $products = $category->products;

      $categoryData = $category->toArray();
      $categoryData['products'] = $products->toArray();

      return response()->json($categoryData, 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['message' => 'Category not found'], 404);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error retrieving products'], 500);
    }
  }
}
