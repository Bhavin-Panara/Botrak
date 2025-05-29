@extends('layouts.newstyle')

@section('title', 'Invoices Show')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Invoices Show</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">Invoices List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Invoice Show</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row g-4">
            <!--begin::Col-->
            <div class="col-md-12">
                <!--begin::Quick Example-->
                <div class="card card-primary card-outline mb-4">
                    <!--begin::Header-->
                    <div class="card-header">
                        <div class="h5 m-0">Invoices <b>#{{ $invoice->invoice_number }}</b></div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-3">
                            <tbody>
                                <tr>
                                    <td class="text-center h5 bg-primary text-white font-weight-bold" colspan="2" style="font-weight: bold;">Invoice Detais</td>
                                </tr>
                                <tr>
                                    <th class="text-center">Invoice Number</th>
                                    <td class="text-center">#{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <th class="text-center">Generate Date</th>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($invoice->generate_date)->format('d M Y H:m:s') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-center">Sent Date</th>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($invoice->sent_date)->format('d M Y H:m:s') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-center">Invoice Status</th>
                                    <td class="text-nowrap text-center"><span class="badge" style="background-color: {{ $invoice->invoice_status === 'sent' ? 'green' : 'red' }};">{{ $invoice->invoice_status }}</span></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped mb-3">
                                    <tbody>
                                        <tr>
                                            <td class="text-center h5 bg-primary text-white font-weight-bold" colspan="2" style="font-weight: bold;">Invoice Sender Detais</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Invoice Number</th>
                                            <td class="text-center">#{{ $invoice->invoice_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Generate Date</th>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($invoice->generate_date)->format('d M Y H:m:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Sent Date</th>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($invoice->sent_date)->format('d M Y H:m:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Invoice Status</th>
                                            <td class="text-nowrap text-center"><span class="badge" style="background-color: {{ $invoice->invoice_status === 'sent' ? 'green' : 'red' }};">{{ $invoice->invoice_status }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped mb-3">
                                    <tbody>
                                        <tr>
                                            <td class="text-center h5 bg-primary text-white font-weight-bold" colspan="2" style="font-weight: bold;">Invoice Receiver Detais</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Invoice Number</th>
                                            <td class="text-center">#{{ $invoice->invoice_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Generate Date</th>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($invoice->generate_date)->format('d M Y H:m:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Sent Date</th>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($invoice->sent_date)->format('d M Y H:m:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Invoice Status</th>
                                            <td class="text-nowrap text-center"><span class="badge" style="background-color: {{ $invoice->invoice_status === 'sent' ? 'green' : 'red' }};">{{ $invoice->invoice_status }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>







    
    <table>
        <tbody>
            <tr>
                <td class="border-none padding-0">
                    <h3 style="font-size: 16px;">From:</h3>
                    <p><b>{{ $invoice->sender->name }}</b></p>
                    <p>CIN: {{ $invoice->sender->organizations->CIN }}</p>
                    <p>GST: {{ $invoice->sender->organizations->GST }}</p>
                    <p>{{ $invoice->sender->email }}</p>
                    <p class="margin-0">{{ $invoice->sender->organizations->phone ?? '-' }}</p>
                </td>
                <td class="border-none padding-0">
                    <h3 class="text-right" style="font-size: 16px;">To:</h3>
                    <p class="text-right"><b>{{ $invoice->receiver->name }}</b></p>
                    <p class="text-right">CIN: {{ $invoice->receiver->CIN }}</p>
                    <p class="text-right">GST: {{ $invoice->receiver->GST }}</p>
                    <p class="text-right">{{ $invoice->receiver->organization_email }}</p>
                    <p class="text-right margin-0">{{ $invoice->receiver->phone }}</p>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="margin-bottom-0">
        <thead>
            <tr>
                <th>Plan Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-wrap-mode: nowrap;">{{ $invoice->companypriceplans->priceplan->name }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->plan_start_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->plan_end_date)->format('d/m/Y') }}</td>
                <td class="text-right">{{ number_format($invoice->amount, 2) ?? '0.00' }} ₹</td>
                <td class="text-right">{{ number_format($invoice->discount, 2) ?? '0.00' }} ₹</td>
                <td class="text-right">{{ number_format($invoice->amount, 2) ?? '0.00' }} ₹</td>
            </tr>
            <tr>
                <td colspan="6" style="padding: 15px;"></td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><b>Discount</b></td>
                <td>{{ number_format($invoice->discount, 2) ?? '0.00' }} ₹</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><b>SGST</b></td>
                <td>{{ number_format($invoice->sgst, 2) ?? '0.00' }} ₹</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><b>CGST</b></td>
                <td>{{ number_format($invoice->cgst, 2) ?? '0.00' }} ₹</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><b>Total Amount</b></td>
                <td><b>{{ number_format($invoice->total_amount, 2) ?? '0.00' }} ₹</b></td>
            </tr>
            <tr>
                <td colspan="6">Amount Chargeable (in words) is <b>{{ convertCurrencyWords($invoice->total_amount ?? 0.00) }}</b></td>
            </tr>
        </tbody>
    </table>

    <table class="margin-top-0">
        <thead>
            <tr>
                <th rowspan="2">Invoice</th>
                <th rowspan="2" class="text-right">Taxable Value</th>
                <th colspan="2" class="text-center">SGST</th>
                <th colspan="2" class="text-center">CGST</th>
                <th rowspan="2" class="text-right">Total Tax Amount</th>
            </tr>
            <tr>
                <th class="text-right">Rate</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#{{ $invoice->invoice_number }}</td>
                <td class="text-right">{{ number_format($invoice->amount, 2) ?? '0.00' }} ₹</td>
                <td class="text-right">9%</td>
                <td class="text-right">{{ number_format($invoice->sgst, 2) ?? '0.00' }} ₹</td>
                <td class="text-right">9%</td>
                <td class="text-right">{{ number_format($invoice->cgst, 2) ?? '0.00' }} ₹</td>
                <td class="text-right">{{ number_format($invoice->tax_total, 2) ?? '0.00' }} ₹</td>
            </tr>
            <tr style="font-weight: bold;">
                <td class="text-right">Total</td>
                <td class="text-right">{{ number_format($invoice->amount, 2) ?? '0.00' }} ₹</td>
                <td></td>
                <td class="text-right">{{ number_format($invoice->sgst, 2) ?? '0.00' }} ₹</td>
                <td></td>
                <td class="text-right">{{ number_format($invoice->cgst, 2) ?? '0.00' }} ₹</td>
                <td class="text-right">{{ number_format($invoice->tax_total, 2) ?? '0.00' }} ₹</td>
            </tr>
            <tr>
                <td colspan="7">Tax Amount (in words) is <b>{{ convertCurrencyWords($invoice->tax_total ?? 0.00) }}</b></td>
            </tr>
        </tbody>
    </table>

    <h3 style="font-size: 16px;">Terms & Conditions</h3>
    <p>Payment is due within 7 days from the invoice date. Late payments may be subject to additional fees. Please contact <b>{{ $invoice->sender->email }}</b> for any questions.</p>










                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Quick Example-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->
@endsection