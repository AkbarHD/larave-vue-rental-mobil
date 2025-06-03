<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);
        return inertia('Admin/PaymentMethods/Index', [
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function create()
    {
        return inertia('Admin/PaymentMethods/Create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type'           => 'required|in:bank_transfer,qris',
            'account_number' => 'nullable|string|max:255',
            'account_name'   => 'nullable|string|max:255',
            'bank_name'      => 'nullable|string|max:255',
            'image'          => 'nullable|image|mimes:png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validatedData['image'] = $image->hashName();
            $image->storeAs('payment_methods', $image->hashName(), 'public');
        }

        PaymentMethod::create($validatedData);

        return redirect()->route('admin.payments.index')->with('success', 'Metode pembayaran berhasil dibuat.');
    }

    public function edit($id)
    {
        $payment = PaymentMethod::findOrFail($id);
        return inertia('Admin/PaymentMethods/Edit', [
            'payment' => $payment,
        ]);
    }

    public function update(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validatedData = $request->validate([
            'type'           => 'required|in:bank_transfer,qris',
            'account_number' => 'nullable|string|max:255',
            'account_name'   => 'required|string|max:255',
            'bank_name'      => 'nullable|string|max:255',
            'image'          => 'nullable|image|mimes:png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($paymentMethod->getRawOriginal('image')) {
                Storage::disk('public')->delete('payment_methods/' . $paymentMethod->getRawOriginal('image'));
            }

            $image = $request->file('image');
            $validatedData['image'] = $image->hashName();
            $image->storeAs('payment_methods', $image->hashName(), 'public');
        }

        $paymentMethod->update($validatedData);

        return redirect()->route('admin.payments.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        if ($paymentMethod->getRawOriginal('image')) {
            Storage::disk('public')->delete('payment_methods/' . $paymentMethod->getRawOriginal('image'));
        }

        if ($paymentMethod->getRawOriginal('qr_image')) {
            Storage::disk('public')->delete('payment_methods/' . $paymentMethod->getRawOriginal('qr_image'));
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
