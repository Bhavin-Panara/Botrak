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
}