<?php

namespace App\Http\Controllers;

use App\Models\PaymentProof;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    // Halaman form upload
    public function create(Transaction $transaction)
    {
        return view('payment_proofs.create', compact('transaction'));
    }

    // Simpan bukti pembayaran
    public function store(Request $request, Transaction $transaction)
    {
        $request->validate([
            'file' => 'required|image|max:2048',
            'note' => 'nullable|string|max:500',
        ]);

        // Simpan file di storage/app/public/payment_proofs
        $filePath = $request->file('file')->store('payment_proofs', 'public');

        // Ambil data user jika ada
        $user = $transaction->user ?? Auth::user();

        // Simpan ke database
        $paymentProof = PaymentProof::create([
            'transaction_id' => $transaction->id,
            'name' => $user->name ?? 'Tidak diketahui',
            'phone' => $user->phone ?? 'Tidak diketahui',
            'file_path' => $filePath,
            'note' => $request->note,
        ]);

        // Redirect ke WhatsApp
        $waMessage = "Halo, saya sudah melakukan pembayaran.\n" .
            "ID Tagihan: {$transaction->id}\n" .
            "Nama: {$paymentProof->name}\n" .
            "No. HP: {$paymentProof->phone}\n" .
            "Catatan: {$request->note}\n" .
            "Silakan cek bukti pembayaran saya.";

        $waLink = "https://wa.me/6285198887963?text=" . urlencode($waMessage);

        return redirect($waLink);
    }
}
