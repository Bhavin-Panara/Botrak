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
                    <div class="card-header d-flex align-items-center">
                        <div class="col-6">
                            <div class="h5 m-0">Invoices <b>#{{ $invoice->invoice_number }}</b></div>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('invoice.download', $invoice->id) }}" class="btn btn-info btn-sm"><i class="bi bi-download"></i> Download Invoice</a>
                        </div>
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
                                    <th class="text-center">Payment Due Date</th>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($invoice->payment_due_date)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-center">Invoice Status</th>
                                    <td class="text-nowrap text-center">
                                        @if($invoice->invoice_status === 'generated')
                                            <span class="badge" style="background-color: blue;">{{ $invoice->invoice_status }}</span>
                                        @elseif($invoice->invoice_status === 'sent')
                                            <span class="badge" style="background-color: green;">{{ $invoice->invoice_status }}</span>
                                        @elseif($invoice->invoice_status === 'cancel')
                                            <span class="badge" style="background-color: red;">{{ $invoice->invoice_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">Invoice Paymaent Status</th>
                                    <td class="text-nowrap text-center">
                                        @if($invoice->payment_status === 'pending')
                                            <span class="badge" style="background-color: blue;">{{ $invoice->payment_status }}</span>
                                        @elseif($invoice->payment_status === 'paid')
                                            <span class="badge" style="background-color: green;">{{ $invoice->payment_status }}</span>
                                        @elseif($invoice->payment_status === 'failed')
                                            <span class="badge" style="background-color: red;">{{ $invoice->payment_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($invoice->payment_status === 'paid')
                                    <tr>
                                        <th class="text-center">Transaction Number</th>
                                        <td class="text-center">{{ $invoice->mark_as_paid }}</td>
                                    </tr>
                                @endif
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
                                            <th class="text-center">Company Name</th>
                                            <td class="text-center">{{ env('SUPER_ADMIN_COMPANY') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Contact Person Name</th>
                                            <td class="text-center">{{ ucwords($invoice->sender->name) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Phone Number</th>
                                            <td class="text-center">{{ env('SUPER_ADMIN_PHONE') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Organization Email</th>
                                            <td class="text-center">{{ $invoice->sender->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">CIN Number</th>
                                            <td class="text-center">{{ env('SUPER_ADMIN_CIN') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">GST Number</th>
                                            <td class="text-center">{{ env('SUPER_ADMIN_GST') }}</td>
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
                                            <th class="text-center">Organization Name</th>
                                            <td class="text-center">{{ ucwords($invoice->receiver->name) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Contact Person Name</th>
                                            <td class="text-center">{{ ucwords($invoice->receiver->contact_person) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Phone Number</th>
                                            <td class="text-center">{{ $invoice->receiver->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Organization Email</th>
                                            <td class="text-center">{{ $invoice->receiver->organization_email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">CIN Number</th>
                                            <td class="text-center">{{ $invoice->receiver->CIN }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">GST Number</th>
                                            <td class="text-center">{{ $invoice->receiver->GST }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped mb-3">
                            <tbody>
                                <tr>
                                    <td class="text-center h5 bg-primary text-white font-weight-bold" colspan="6" style="font-weight: bold;">Selected Plan Detais</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">Plan Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                                <tr>
                                    <td class="text-nowrap">{{ ucwords($invoice->companypriceplans->priceplan->name) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->plan_start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->plan_end_date)->format('d M Y') }}</td>
                                    <td class="text-end">{{ number_format($invoice->amount, 2) ?? '0.00' }} ₹</td>
                                    <td class="text-end">{{ number_format($invoice->discount, 2) ?? '0.00' }} ₹</td>
                                    <td class="text-end">{{ number_format($invoice->amount, 2) ?? '0.00' }} ₹</td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="padding: 20px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><b>Discount</b></td>
                                    <td>{{ number_format($invoice->discount, 2) ?? '0.00' }} ₹</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><b>SGST</b></td>
                                    <td>{{ number_format($invoice->sgst, 2) ?? '0.00' }} ₹</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><b>CGST</b></td>
                                    <td>{{ number_format($invoice->cgst, 2) ?? '0.00' }} ₹</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><b>Total Amount</b></td>
                                    <td><b>{{ number_format($invoice->total_amount, 2) ?? '0.00' }} ₹</b></td>
                                </tr>
                                <tr>
                                    <td colspan="6">Amount Chargeable (in words) is <b>{{ convertCurrencyWords($invoice->total_amount ?? 0.00) }}</b></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td class="text-center h5 bg-primary text-white font-weight-bold" colspan="7" style="font-weight: bold;">Selected Plan Tax Detais</td>
                                </tr>
                                <tr>
                                    <th rowspan="2">Invoice</th>
                                    <th rowspan="2" class="text-end">Taxable Value</th>
                                    <th colspan="2" class="text-center">SGST</th>
                                    <th colspan="2" class="text-center">CGST</th>
                                    <th rowspan="2" class="text-end">Total Tax Amount</th>
                                </tr>
                                <tr>
                                    <th class="text-end">Rate</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Rate</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#{{ $invoice->invoice_number }}</td>
                                    <td class="text-end">{{ number_format($invoice->amount, 2) ?? '0.00' }} ₹</td>
                                    <td class="text-end">9%</td>
                                    <td class="text-end">{{ number_format($invoice->sgst, 2) ?? '0.00' }} ₹</td>
                                    <td class="text-end">9%</td>
                                    <td class="text-end">{{ number_format($invoice->cgst, 2) ?? '0.00' }} ₹</td>
                                    <td class="text-end">{{ number_format($invoice->tax_total, 2) ?? '0.00' }} ₹</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="text-end">Total</td>
                                    <td class="text-end">{{ number_format($invoice->amount, 2) ?? '0.00' }} ₹</td>
                                    <td></td>
                                    <td class="text-end">{{ number_format($invoice->sgst, 2) ?? '0.00' }} ₹</td>
                                    <td></td>
                                    <td class="text-end">{{ number_format($invoice->cgst, 2) ?? '0.00' }} ₹</td>
                                    <td class="text-end">{{ number_format($invoice->tax_total, 2) ?? '0.00' }} ₹</td>
                                </tr>
                                <tr>
                                    <td colspan="7">Tax Amount (in words) is <b>{{ convertCurrencyWords($invoice->tax_total ?? 0.00) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
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