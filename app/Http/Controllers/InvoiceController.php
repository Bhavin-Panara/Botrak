<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoices::with(['sender', 'receiver', 'companypriceplans', 'companypriceplans.priceplan'])->get();
        return view('invoice.index', compact('invoices'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoices::with(['sender', 'receiver', 'companypriceplans', 'sender.organizations', 'companypriceplans.priceplan'])->where('id', $id)->first();
        return view('invoice.show', compact('invoice'));
    }
    
    public function download($id)
    {
        $invoice = Invoices::with(['sender', 'receiver', 'companypriceplans', 'sender.organizations', 'companypriceplans.priceplan'])->where('id', $id)->first();

        $pdf = Pdf::loadView('invoice.downloadpdf', compact('invoice'));

        return $pdf->download('invoice_#'.$invoice->invoice_number.'.pdf');
    }

    public function mark_as_paid(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'mark_as_paid' => 'required',
        ],[
            'mark_as_paid.required' => 'Transaction number is required to mark this invoice as paid.',
        ]);

        $user_invoice = Invoices::findOrFail($validated['invoice_id']);
        $user_invoice->mark_as_paid = $validated['mark_as_paid'];

        if ($user_invoice->save()) {
            $user_invoice->payment_status = 'paid';
            $user_invoice->save();
        }

        return redirect()->back()->with('success', 'Mark as paid successfully.');
    }
}