<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Customer;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    /**
     * Menampilkan semua data follow up
     */
    public function index()
    {
        $followUps = FollowUp::with('customer')->latest()->paginate(10);

        $newCustomerDetails = Customer::where('status', 'lead')
            ->where('notified', false)
            ->latest()
            ->take(5)
            ->get();

        // Tandai customer sebagai sudah diberi notifikasi
        if ($newCustomerDetails->count() > 0) {
            foreach ($newCustomerDetails as $customer) {
                $customer->update(['notified' => true]);
            }

            session()->flash(
                'new_customers_data',
                $newCustomerDetails->map(function ($customer) {
                    return [
                        'name' => $customer->full_name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                    ];
                })
            );
        }


        return view('admin.followups.index', [
            'followUps' => $followUps,
            'newCustomers' => $newCustomerDetails->count(),
            'newCustomerDetails' => $newCustomerDetails
        ]);
    }



    /**
     * Form tambah follow up baru
     */
    public function create()
    {
        $customers = Customer::orderBy('full_name', 'asc')->get();

        return view('admin.followups.create', compact('customers'));
    }

    /**
     * Simpan data follow up baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'follow_up_date' => 'required|date',
            'status' => 'required|in:pending,contacted,negotiation,closed',
            'note' => 'nullable|string',
        ]);

        // Simpan data follow up
        $followUp = FollowUp::create($request->all());

        // Update status customer menjadi 'prospect' jika masih 'lead'
        $customer = Customer::find($request->customer_id);
        if ($customer->status === 'lead') {
            $customer->update(['status' => 'prospect']);
        }

        return redirect()->route('followups.index')->with('success', 'Follow-up berhasil ditambahkan!');
    }


    /**
     * Form edit follow up
     */
    public function edit(FollowUp $followup)
    {
        $customers = Customer::all();
        return view('admin.followups.edit', compact('followup', 'customers'));
    }

    /**
     * Update data follow up
     */
    public function update(Request $request, FollowUp $followup)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'follow_up_date' => 'required|date',
            'status' => 'required|in:pending,contacted,negotiation,closed',
            'note' => 'nullable|string',
        ]);

        $followup->update($validated);

        // Update status customer menjadi 'prospect' jika masih 'lead'
        $customer = Customer::find($request->customer_id);
        if ($customer->status === 'lead') {
            $customer->update(['status' => 'prospect']);
        }

        return redirect()->route('followups.index')->with('success', 'Follow-up berhasil diperbarui.');
    }


    /**
     * Hapus data follow up
     */
    public function destroy(FollowUp $followup)
    {
        $followup->delete();
        return redirect()->route('followups.index')->with('success', 'Follow-up berhasil dihapus.');
    }
}
