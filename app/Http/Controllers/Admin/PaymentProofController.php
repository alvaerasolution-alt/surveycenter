<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProof;
use Illuminate\Http\Request;

class PaymentProofController extends Controller
{
    public function index()
    {
        $proofs = PaymentProof::with('transaction')->latest()->get();
        return view('admin.payment_proofs.index', compact('proofs'));
    }
}
