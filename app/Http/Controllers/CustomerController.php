<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use App\Models\Tag;

class CustomerController extends Controller
{
    public function index(): View 
    {
        return view('customers.index', [
            'customers' => Customer::latest()->paginate(6),
        ]);
    }

    public function create(Request $customer): View
    {
        $tags = Tag::all();

        return view('customers.create', [
            'customer' => $customer,
            'tags' => $tags
        ]);
    }

    public function store(StoreCustomerRequest $request) : RedirectResponse
    {
        Customer::create($request->all());
        return redirect()->route('customer.index')
        ->withSuccess('Cliente creado');
    }

    public function edit(Customer $customer) : View
    {
        $tags = Tag::all();
        return view('customers.edit', [
            'customer' => $customer,
            'tags' => $tags
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer) : RedirectResponse
    {
        $customer->update($request->all());
        return redirect()->route('customer.index')
                ->withSuccess('cliente editado con éxito.');
    }

    public function destroy(Customer $customer) : RedirectResponse
    {
        $customer->delete();
        return redirect()->route('customer.index')
                ->withSuccess('Cliente eliminado con éxito');
    }
}
