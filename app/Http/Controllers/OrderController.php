<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ApiResponse;

    // GET /orders  atau  GET /orders?customer_id=1  (poin 2a)
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->query('customer_id') !== null) {
            $query->where('customer_id', $request->query('customer_id'));
        }

        return $this->respond(['status' => true, 'data' => $query->get()->toArray()]);
    }

    // GET /orders/{id}
    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->respond(['status' => false, 'message' => 'Order tidak ditemukan'], 404);
        }
        return $this->respond(['status' => true, 'data' => $order->toArray()]);
    }

    // POST /orders
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'customer_id' => 'required|integer|exists:customers,id',
            'menu_name'   => 'required|string|max:100',
            'quantity'    => 'integer|min:1',
            'total_price' => 'numeric',
            'status'      => 'in:pending,processing,done',
        ]);
        if ($v->fails()) {
            return $this->respond(['status' => false, 'errors' => $v->errors()->toArray()], 422);
        }

        $order = Order::create($request->only((new Order)->getFillable()));
        return $this->respond([
            'status' => true, 'message' => 'Order dibuat', 'data' => $order->fresh()->toArray(),
        ], 201);
    }

    // PUT /orders/{id}
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->respond(['status' => false, 'message' => 'Order tidak ditemukan'], 404);
        }

        $v = Validator::make($request->all(), [
            'customer_id' => 'integer|exists:customers,id',
            'menu_name'   => 'string|max:100',
            'quantity'    => 'integer|min:1',
            'total_price' => 'numeric',
            'status'      => 'in:pending,processing,done',
        ]);
        if ($v->fails()) {
            return $this->respond(['status' => false, 'errors' => $v->errors()->toArray()], 422);
        }

        $order->update($request->only($order->getFillable()));
        return $this->respond([
            'status' => true, 'message' => 'Order diupdate', 'data' => $order->fresh()->toArray(),
        ]);
    }

    // DELETE /orders/{id}
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->respond(['status' => false, 'message' => 'Order tidak ditemukan'], 404);
        }
        $order->delete();
        return $this->respond(['status' => true, 'message' => 'Order dihapus']);
    }
}