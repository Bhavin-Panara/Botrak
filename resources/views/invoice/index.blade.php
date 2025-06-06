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

@if (session('success'))
    <div class="alert alert-success mx-4 fade-message" role="alert">{{ session('success') }}</div>
@endif

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
                                    <th class="text-nowrap text-center">SGST</th>
                                    <th class="text-nowrap text-center">CGST</th>
                                    <th class="text-nowrap text-center">Total Tax</th>
                                    <th class="text-nowrap text-center">Total Amount</th>
                                    <th class="text-nowrap text-center">Payment Due Date</th>
                                    <th class="text-nowrap text-center">Payment Status</th>
                                    <th class="text-nowrap text-center">Mark As Paid</th>
                                    <th class="text-nowrap text-center">Actions</th>
                                    <th class="text-nowrap text-center">Payment Reminder</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    <tr class="align-middle">
                                        <td class="text-nowrap text-center">{{ $invoice->invoice_number }}</td>
                                        <td class="text-nowrap text-center">{{ \Carbon\Carbon::parse($invoice->generate_date)->format('d-m-Y') }}</td>
                                        <td class="text-nowrap text-center">
                                            @if($invoice->invoice_status === 'generated')
                                                <span class="badge" style="background-color: blue;">{{ $invoice->invoice_status }}</span>
                                            @elseif($invoice->invoice_status === 'sent')
                                                <span class="badge" style="background-color: green;">{{ $invoice->invoice_status }}</span>
                                            @elseif($invoice->invoice_status === 'cancel')
                                                <span class="badge" style="background-color: red;">{{ $invoice->invoice_status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap text-center">{{ $invoice->sender->email }}</td>
                                        <td class="text-nowrap text-center">{{ $invoice->receiver->organization_email }}</td>
                                        <td class="text-nowrap text-center">{{ ucwords($invoice->companypriceplans->priceplan->name) }}</td>
                                        <td class="text-nowrap text-center">{{ $invoice->plan_start_date ? \Carbon\Carbon::parse($invoice->plan_start_date)->format('d-m-Y') : '-' }}</td>
                                        <td class="text-nowrap text-center">{{ $invoice->plan_end_date ? \Carbon\Carbon::parse($invoice->plan_end_date)->format('d-m-Y') : '-' }}</td>
                                        <td class="text-nowrap text-center">{{ $invoice->amount }} &#8377;</td>
                                        <td class="text-nowrap text-center">{{ $invoice->discount }} &#8377;</td>
                                        <td class="text-nowrap text-center">{{ $invoice->sgst }} &#8377;</td>
                                        <td class="text-nowrap text-center">{{ $invoice->cgst }} &#8377;</td>
                                        <td class="text-nowrap text-center">{{ $invoice->tax_total }} &#8377;</td>
                                        <td class="text-nowrap text-center">{{ $invoice->total_amount }} &#8377;</td>
                                        <td class="text-nowrap text-center">{{ \Carbon\Carbon::parse($invoice->payment_due_date)->format('d-m-Y') }}</td>
                                        <td class="text-nowrap text-center">
                                            @if($invoice->payment_status === 'pending')
                                                <span class="badge" style="background-color: blue;">{{ $invoice->payment_status }}</span>
                                            @elseif($invoice->payment_status === 'paid')
                                                <span class="badge" style="background-color: green;">{{ $invoice->payment_status }}</span>
                                            @elseif($invoice->payment_status === 'failed')
                                                <span class="badge" style="background-color: red;">{{ $invoice->payment_status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            @if($invoice->payment_status === 'pending' && $invoice->invoice_status != 'cancel')
                                                <span class="badge" style="background-color: blue; cursor: pointer;" onclick="openModal({{ $invoice->id }})">
                                                    <i class="bi bi-currency-rupee" style="font-size: large;"></i>
                                                </span>
                                            @elseif($invoice->payment_status === 'paid')
                                                <span class="badge" style="background-color: green; border-radius: 100%; padding: 5px;">
                                                    <i class="bi bi-check2-circle" style="font-size: large;"></i>
                                                </span>
                                            @elseif($invoice->payment_status === 'failed' || $invoice->invoice_status === 'cancel')
                                                <span class="badge" style="background-color: red; border-radius: 100%; padding: 5px;">
                                                    <i class="bi bi-x-circle" style="font-size: large;"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-primary btn-sm" title="Show Invoice"><i class="nav-icon bi bi-eye"></i></a>
                                        </td>
                                        <td class="text-nowrap text-center">{{ $invoice->payment_reminder ?? '-' }}</td>
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

<!--begin::Modal-->
<div class="modal fade" id="markaspaidModal" tabindex="-1" aria-labelledby="markaspaidModalLabel" aria-hidden="true">
    <!--begin::Modal Dialog-->
    <div class="modal-dialog">
        <!--begin::Modal Form-->
        <form id="markaspaidForm" method="POST" action="{{ route('invoice.mark_as_paid') }}">
            @csrf

            <input type="hidden" name="invoice_id" id="modal_invoice_id" value="{{ old('invoice_id') }}">
            <!--begin::Modal Content-->
            <div class="modal-content" style="border-top: 3px solid blue;">
                <!--begin::Modal Header-->
                <div class="modal-header">
                    <h5 class="modal-title" id="markaspaidModalLabel">Invoice Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!--end::Modal Header-->
                <!--begin::Modal Body-->
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Transaction Number<span class="text-danger"> *</span></label>
                        <input type="text" name="mark_as_paid" class="form-control" value="{{ old('mark_as_paid') }}" placeholder="Transaction Number" id="markAsPaidInput"/>
                        @if ($errors->has('mark_as_paid') || $errors->has('invoice_id'))
                            <div id="validationcnError" class="form-text text-danger">
                                {!! $errors->first('mark_as_paid') . '' . $errors->first('invoice_id') !!}
                            </div>
                        @endif
                        @if ($errors->any())
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                const modal = new bootstrap.Modal(document.getElementById('markaspaidModal'));
                                modal.show();
                            });
                            </script>
                        @endif
                        <div id="validationError" class="form-text text-danger" style="display:none;"></div>
                    </div>
                </div>
                <!--end::Modal Body-->
                <!--begin::Modal Footer-->
                <div class="modal-footer" style="justify-content: flex-start;">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                <!--end::Modal Footer-->
            </div>
            <!--end::Modal Content-->
        </form>
        <!--end::Modal Form-->
    </div>
    <!--end::Modal Dialog-->
</div>
<!--end::Modal-->
@endsection

@section('pagescript')
<script>
    function openModal(invoiceId) {
        document.getElementById('modal_invoice_id').value = invoiceId;
        document.getElementById('validationError').style.display = 'none';
        new bootstrap.Modal(document.getElementById('markaspaidModal')).show();
    }

    document.getElementById('markaspaidForm').addEventListener('submit', function (e) {
        const markAsPaidValue = document.getElementById('markAsPaidInput').value.trim();

        if (!markAsPaidValue) {
            e.preventDefault();
            // document.getElementById('validationcnError')?.style.display = 'none';
            document.getElementById('validationcnError')?.classList.add('d-none');
            document.getElementById('validationError').textContent = 'Transaction number field is required.';
            document.getElementById('validationError').style.display = 'block';
        }
    });
</script>
@endsection