<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;
use App\Models\Transaction;
use App\Models\FollowUp;
use Illuminate\Support\Facades\Auth;

class CRMController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get follow-ups data
        $followUps = FollowUp::with('customer')
            ->latest('follow_up_date')
            ->take(5)
            ->get();

        // Get customers with paid transactions
        $customerAlready = User::whereHas('transactions', function ($query) {
            $query->where('status', 'paid');
        })->with(['transactions' => function ($query) {
            $query->where('status', 'paid');
        }])->get();

        $stats = [
            ['title' => 'CUSTOMER SUDAH BAYAR', 'value' => $customerAlready->count()],
            ['title' => 'TOTAL PEMBAYARAN', 'value' => 'Rp ' . number_format($customerAlready->sum(function($user) { return $user->transactions->sum('amount'); }), 0, ',', '.')],
            ['title' => 'TRANSAKSI BERHASIL', 'value' => $customerAlready->sum(function($user) { return $user->transactions->count(); })],
        ];

        return view('admin.crm.dashboard', compact('customerAlready', 'followUps', 'stats'));
    }

    public function clientMenu()
    {
        try {
            // Get follow-ups data
            $followUps = FollowUp::with('customer')
                ->latest('follow_up_date')
                ->take(5)
                ->get();

            // Get users with paid transactions
            $customerAlready = User::whereHas('transactions', function ($query) {
                $query->where('status', 'paid');
            })->with(['transactions' => function ($query) {
                $query->where('status', 'paid');
            }])->latest()->take(5)->get();

            return view('admin.crm', compact('followUps', 'customerAlready'));
        } catch (\Exception $e) {
            // Fallback dengan data kosong jika ada error
            return view('admin.crm', [
                'followUps' => collect(),
                'customerAlready' => collect()
            ]);
        }
    }

    public function customerAlready()
    {
        // Get users with paid transactions
        $users = User::whereHas('transactions', function ($query) {
            $query->where('status', 'paid');
        })->with(['transactions' => function ($query) {
            $query->where('status', 'paid');
        }])->paginate(10);

        return view('admin.crm.customer-already', compact('users'));
    }
}
