<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
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
        }])->latest()->get();

        // Pipeline data from customers table
        $pipeline = [
            'lead'      => Customer::where('status', 'lead')->count(),
            'prospect'  => Customer::where('status', 'prospect')->count(),
            'customer'  => Customer::where('status', 'customer')->count(),
        ];

        // Transaction status counts
        $transactionStats = [
            'pending' => Transaction::where('status', 'pending')->count(),
            'paid'    => Transaction::where('status', 'paid')->count(),
        ];

        // Monthly revenue for the last 6 months
        $monthlyRevenue = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->translatedFormat('M Y');
            $monthlyRevenue[] = Transaction::where('status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
        }

        // Follow-up status counts
        $followUpStats = [
            'pending'     => FollowUp::where('status', 'pending')->count(),
            'contacted'   => FollowUp::where('status', 'contacted')->count(),
            'negotiation' => FollowUp::where('status', 'negotiation')->count(),
            'closed'      => FollowUp::where('status', 'closed')->count(),
        ];

        $stats = [
            ['title' => 'CUSTOMER SUDAH BAYAR', 'value' => $customerAlready->count()],
            ['title' => 'TOTAL PEMBAYARAN', 'value' => 'Rp ' . number_format($customerAlready->sum(function($user) { return $user->transactions->sum('amount'); }), 0, ',', '.')],
            ['title' => 'TRANSAKSI BERHASIL', 'value' => $customerAlready->sum(function($user) { return $user->transactions->count(); })],
            ['title' => 'TOTAL LEAD', 'value' => $pipeline['lead']],
            ['title' => 'TOTAL PROSPECT', 'value' => $pipeline['prospect']],
            ['title' => 'TOTAL CUSTOMER', 'value' => $pipeline['customer']],
        ];

        return view('admin.crm.dashboard', compact(
            'customerAlready', 'followUps', 'stats',
            'pipeline', 'transactionStats', 'monthlyRevenue', 'monthLabels', 'followUpStats'
        ));
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
