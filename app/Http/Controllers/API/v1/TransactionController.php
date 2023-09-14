<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'role:customer|seller']);
  }

  /**
   * Display a listing of the resource.
   */
  public function index($status)
  {
    try {
      $user = Auth::user();

      if (!$user) {
        return response()->json([
          'status' => 401,
          'message' => 'Unauthorized',
        ], 401);
      }

      $orders = Order::where('user_id', $user->id)
        ->where('status', $status)
        ->get();

      if ($orders->isEmpty()) {
        return response()->json([
          'status' => 200,
          'message' => 'No orders found',
          'user' => [
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
          ],
        ], 200);
      }

      if ($status == 'delivered') {
        return response()->json([
          'status' => 200,
          'message' => 'Orders retrieved successfully',
          'status_transaction' => 'transaction completed',
          'count' => $orders->count(),
          'user' => [
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
          ],
          'orders' => $orders,
        ], 200);
      }

      return response()->json([
        'status' => 200,
        'message' => 'Orders retrieved successfully',
        'status_transaction' => $status,
        'count' => $orders->count(),
        'user' => [
          'id' => Auth::user()->id,
          'name' => Auth::user()->name,
          'email' => Auth::user()->email,
        ],
        'orders' => $orders,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 500,
        'message' => 'Error retrieving orders ' . $e->getMessage(),
      ], 500);
    }
  }
}
