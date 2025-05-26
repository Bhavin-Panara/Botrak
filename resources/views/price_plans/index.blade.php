@extends('layouts.newstyle')

@section('title', 'Price Plans List')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Price Plans List</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Price Plans</li>
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
                    <div class="card-header d-flex align-items-center">
                        <div class="col-6">
                            <h3 class="card-title">Price Plans List</h3>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('price_plans.create') }}" class="btn btn-primary btn-sm">Add Price Plans</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Plan Type</th>
                                    <th>Unlimited</th>
                                    <th>Per Asset Price</th>
                                    <th>Plan Price</th>
                                    <th>Total Plan Days</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plans as $plan)
                                    <tr class="align-middle">
                                        <td>{{ $plan->name }}</td>
                                        <td>{{ ucfirst($plan->plan_type) }}</td>
                                        <td>{{ $plan->is_unlimited ? 'Yes' : 'No' }}</td>
                                        <td>{{ $plan->per_asset_price ? '₹'.$plan->per_asset_price : '-' }}</td>
                                        <td>{{ $plan->unlimited_price ? '₹'.$plan->unlimited_price : '-' }}</td>
                                        <td>{{ $plan->total_days ? $plan->total_days.' Days' : '-' }}</td>
                                        <td>
                                            <a href="{{ route('price_plans.edit', $plan->id) }}" class="btn btn-warning btn-sm" title="Edit Plan"><i class="nav-icon bi bi-pencil-square"></i></a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm"
                                                title="Delete Plan"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-id="{{ $plan->id }}"
                                                data-route="{{ route('price_plans.destroy', $plan->id) }}">
                                                <i class="nav-icon bi bi-trash"></i>
                                            </button>
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