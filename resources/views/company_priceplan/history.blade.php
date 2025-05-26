@extends('layouts.newstyle')

@section('title', 'Organization Price Plans History')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Organization Price Plans History</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('company_priceplan.index') }}">Organization Price Plans List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Organization Price Plans History</li>
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
                            <h3 class="card-title">Organization Price Plans History</h3>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('company_priceplan.create') }}" class="btn btn-primary btn-sm">Assign New Plan</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Organization Name</th>
                                    <th>Plans Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Plan Status</th>
                                    <!-- <th>Actions</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assign_plans as $assign_plan)
                                    <tr class="align-middle">
                                        <td>{{ $assign_plan->organizations->name }}</td>
                                        <td>{{ $assign_plan->priceplan->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($assign_plan->start_date)->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($assign_plan->end_date)->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ ucfirst($assign_plan->status) }}</td>
                                        <!-- <td>
                                            <a href="{{ route('company_priceplan.edit', $assign_plan->id) }}" class="btn btn-warning btn-sm" title="Edit History"><i class="nav-icon bi bi-pencil-square"></i></a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm"
                                                title="Delete Plan"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-id="{{ $assign_plan->id }}"
                                                data-route="{{ route('company_priceplan.destroy', $assign_plan->id) }}">
                                                <i class="nav-icon bi bi-trash"></i>
                                            </button>
                                        </td> -->
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