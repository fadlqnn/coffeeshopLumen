<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    use ApiResponse;

    // GET /customers
    public function index()
    {
        return $this->respond(['status' => true, 'data' => Customer::all()->toArray()]);
    }

    // GET /customers/{id}
    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return $this->respond(['status' => false, 'message' => 'Customer tidak ditemukan'], 404);
        }
        return $this->respond(['status' => true, 'data' => $customer->toArray()]);
    }

    // POST /customers
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:customers,email',
            'gender'           => 'in:Laki-laki,Perempuan',
            'birth_date'       => 'date',
            'membership_level' => 'in:Regular,Silver,Gold',
            'loyalty_points'   => 'integer',
        ]);
        if ($v->fails()) {
            return $this->respond(['status' => false, 'errors' => $v->errors()->toArray()], 422);
        }

        $customer = Customer::create($request->only((new Customer)->getFillable()));
        return $this->respond([
            'status' => true, 'message' => 'Customer dibuat', 'data' => $customer->fresh()->toArray(),
        ], 201);
    }

    // PUT /customers/{id}
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return $this->respond(['status' => false, 'message' => 'Customer tidak ditemukan'], 404);
        }

        $v = Validator::make($request->all(), [
            'name'             => 'string|max:100',
            'email'            => 'email|unique:customers,email,' . $id,
            'gender'           => 'in:Laki-laki,Perempuan',
            'birth_date'       => 'date',
            'membership_level' => 'in:Regular,Silver,Gold',
            'loyalty_points'   => 'integer',
        ]);
        if ($v->fails()) {
            return $this->respond(['status' => false, 'errors' => $v->errors()->toArray()], 422);
        }

        $customer->update($request->only($customer->getFillable()));
        return $this->respond([
            'status' => true, 'message' => 'Customer diupdate', 'data' => $customer->fresh()->toArray(),
        ]);
    }

    // DELETE /customers/{id}
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return $this->respond(['status' => false, 'message' => 'Customer tidak ditemukan'], 404);
        }
        $customer->delete();
        return $this->respond(['status' => true, 'message' => 'Customer dihapus']);
    }
}