@extends('layouts.newstyle')

@section('title', 'Invoices List')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Invoices List</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Invoices List</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <!-- /.card-header -->
                    <div class="card-body overflow-x-auto">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-nowrap text-center">Invoice Number</th>
                                    <th class="text-nowrap text-center">Generate Date</th>
                                    <th class="text-nowrap text-center">Invoice Status</th>
                                    <th class="text-nowrap text-center">Invoice Sender</th>
                                    <th class="text-nowrap text-center">Invoice Receiver</th>
                                    <th class="text-nowrap text-center">Plan Name</th>
                                    <th class="text-nowrap text-center">Plan Start Date</th>
                                    <th class="text-nowrap text-center">Plan End Date</th>
                                    <th class="text-nowrap text-center">Amount</th>
                                    <th class="text-nowrap text-center">Discount</th>
                                    <th class="text-nowrap text-center">Total Amount</th>
                                    <th class="text-nowrap text-center">Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    <tr class="align-middle">
                                        <td class="text-nowrap text-center">{{ $invoice->invoice_number }}</td>
                                        <td class="text-nowrap text-center">{{ \Carbon\Carbon::parse($invoice->generate_date)->format('d-m-Y') }}</td>
                                        <td class="text-nowrap text-center"><span class="badge" style="background-color: {{ $invoice->invoice_status === 'sent' ? 'green' : 'red' }};">{{ $invoice->invoice_status }}</span></td>
                                        <td class="text-nowrap text-center">{{ $invoice->sender->email }}</td>
                                        <td class="text-nowrap text-center">{{ $invoice->receiver->organization_email }}</td>
                                        <td class="text-nowrap text-center">{{ $invoice->companypriceplans->priceplan->name }}</td>
                                        <td class="text-nowrap text-center">{{ $invoice->plan_start_date ? \Carbon\Carbon::parse($invoice->plan_start_date)->format('d-m-Y') : '-' }}</td>
                                        <td class="text-nowrap text-center">{{ $invoice->plan_end_date ? \Carbon\Carbon::parse($invoice->plan_end_date)->format('d-m-Y') : '-' }}</td>
                                        <td class="text-nowrap text-center">{{ $invoice->amount }} &#8377;</td>
                                        <td class="text-nowrap text-center">{{ $invoice->discount }} &#8377;</td>
                                        <td class="text-nowrap text-center">{{ $invoice->total_amount }} &#8377;</td>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <!-- <div class="card-footer clearfix">
                        <ul class="pagination pagination-sm m-0 float-end">
                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                        </ul>
                    </div> -->
                </div>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->
@endsection