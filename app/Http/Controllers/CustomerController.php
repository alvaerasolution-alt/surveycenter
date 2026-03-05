<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('crm.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('crm.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:150',
            'email'     => 'required|email|unique:customers,email',
            'phone'     => 'nullable|string|max:20',
        ]);

        $customer = Customer::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'status'    => 'lead',
            'source'    => $request->source ?? 'Website',
            'notes'     => $request->notes,
        ]);

        // Buat follow-up otomatis setelah customer dibuat
        FollowUp::create([
            'customer_id' => $customer->id,
            'follow_up_date' => now(),
            'status' => 'pending',
            'note' => 'Follow-up otomatis setelah customer dibuat'
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan!');
    }

    public function show(Customer $customer)
    {
        return view('crm.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('crm.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'full_name' => 'required|string|max:150',
            'email'     => 'required|email|unique:customers,email,' . $customer->id,
            'phone'     => 'nullable|string|max:20',
            'status'    => 'required|in:lead,prospect,customer',
        ]);

        $customer->update($request->all());

        // Buat follow-up otomatis setelah customer diupdate
        \App\Models\FollowUp::create([
            'customer_id' => $customer->id,
            'follow_up_date' => now(),
            'status' => 'pending',
            'note' => 'Follow-up otomatis setelah customer diupdate'
        ]);

        return redirect()->route('customers.index')->with('success', 'Data berhasil diupdate!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus!');
    }
}
