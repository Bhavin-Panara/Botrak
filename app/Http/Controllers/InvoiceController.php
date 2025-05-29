<?php

namespace App\Http\Controllers;

use App\Models\Invoices;

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

        dd($invoice);

        return view('invoice.show', compact('invoice'));
    }
}