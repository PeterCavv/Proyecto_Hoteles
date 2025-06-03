<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class CustomerController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of Customers.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $this->authorize('index', Customer::class);

        $customers = Customer::all()->load('user');

        return Inertia::render('Admin/CustomerIndex', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new Customer.
     *
     * @return \Inertia\Response
     */
    public function create(Create $request)
    {
        $this->authorize('create', Customer::class);

        return Inertia::render('Admin/CustomerCreate');
    }

    /**
     * Store a newly created Customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', Customer::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone_number' => 'nullable|string|max:20',
        ]);

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified Customer.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Inertia\Response
     */
    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);

        return Inertia::render('Admin/CustomerEdit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified Customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone_number' => 'nullable|string|max:20',
        ]);

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

/**
     * Remove the specified Customer from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

}
