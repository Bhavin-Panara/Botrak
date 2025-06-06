@extends('layouts.newstyle')

@section('title', 'Report')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Report</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Report</li>
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
                    <div class="card-header d-flex align-items-center">
                        <div class="col-6">
                            <h3 class="card-title">Assets Report</h3>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="col-md-6 pe-2">
                                <div class="mb-2">
                                    <label class="form-label">From Date<span class="text-danger"> *</span></label>
                                    <input type="date" name="from_date" id="from_date" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-md-6 ps-2">
                                <div class="mb-2">
                                    <label class="form-label">To Date<span class="text-danger"> *</span></label>
                                    <input type="date" name="to_date" id="to_date" class="form-control" value="">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-danger mt-1 mb-0" id="error_msg" role="alert" style="display: none;"></div>

                        <div id="loader" class="text-center mt-3" style="display: none;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div id="total_count_section" style="display: none;">
                            <table class="table table-bordered table-striped mt-3">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Organization Name</th>
                                        <th>Asset Counts</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->
@endsection

@section('pagescript')
<script>
$(document).ready(function () {
    function validateAndFetch() {
        let fromDate = $('#from_date').val();
        let toDate = $('#to_date').val();

        // Always hide error message first
        $('#error_msg').hide().text('');

        if (fromDate && toDate) {
            if (new Date(fromDate) > new Date(toDate)) {
                $('#total_count_section').hide();
                $('#error_msg').text('From Date cannot be after To Date').show();
                return;
            }

            $.ajax({
                url: "{{ route('report.asset_count') }}",
                method: 'GET',
                data: { from_date: fromDate, to_date: toDate },
                beforeSend: function () {
                    $('#loader').show();
                    $('#table_body').html('');
                },
                success: function (response) {
                    $('#loader').hide();
                    $('#total_count_section').show();
                    $('#error_msg').hide().text('');

                    let tableBody = '';
                    let i = 1;
                    let total = response.total;

                    response.data.forEach(function (row) {
                        tableBody += `<tr><td>${i++}</td><td>${row.organization_name}</td><td>${row.asset_count}</td></tr>`;
                    });

                    tableBody += `<tr><th colspan="2" class="text-end">Total Asset Counts</th><th>${total}</th></tr>`;

                    $('#table_body').html(tableBody);
                },
                error: function () {
                    $('#loader').hide();
                    $('#total_count_section').hide();
                    $('#error_msg').text('Error fetching asset count.').show();
                }
            });
        } else {
            $('#total_count_section').hide();
            $('#error_msg').text('Please select both From and To dates.').show();
        }
    }

    $('#from_date, #to_date').on('change', function () {
        validateAndFetch();
    });
});
</script>
@endsection