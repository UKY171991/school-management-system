<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use Illuminate\Http\Request;

class FeePaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $payments = FeePayment::with('student', 'feeStructure')->latest()->get();
            return response()->json($payments);
        }
        return view('fee-payments.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:Paid,Partial,Pending',
        ]);

        $payment = FeePayment::create($validated);

        return response()->json(['success' => 'Payment recorded successfully.', 'payment' => $payment]);
    }

    public function show($id)
    {
        return response()->json(FeePayment::with('student', 'feeStructure')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $payment = FeePayment::findOrFail($id);
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:Paid,Partial,Pending',
        ]);

        $payment->update($validated);

        return response()->json(['success' => 'Payment record updated.', 'payment' => $payment]);
    }

    public function destroy($id)
    {
        $payment = FeePayment::findOrFail($id);
        $payment->delete();
        return response()->json(['success' => 'Payment record deleted.']);
    }
}
