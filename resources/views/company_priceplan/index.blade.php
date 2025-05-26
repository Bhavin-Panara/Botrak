@extends('layouts.newstyle')

@section('title', 'Organization Price Plans List')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Organization Price Plans List</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Organization Price Plans</li>
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
                            <h3 class="card-title">Company Price Plans List</h3>
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
                                    <th>History</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="align-middle">
                                        <?php /* @if($user->status === 'continue') */ ?>
                                            <td>{{ $user->organizations->name }}</td>
                                            <td>{{ $user->priceplan->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($user->start_date)->format('d/m/Y') ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($user->end_date)->format('d/m/Y') ?? '-' }}</td>
                                            <td>{{ ucfirst($user->status) }}</td>
                                            <td>
                                                <a href="{{ route('company_priceplan.history', $user->organizations->id) }}" class="btn btn-info btn-sm">View History</a>
                                            </td>
                                        <?php /*
                                        @elseif($user->status === 'completed')
                                            <td>{{ $user->organizations->name }}</td>
                                            <td colspan="4" class="text-center"><strong>{{ $user->organizations->name }}</strong> has no active plan currently.</td>
                                            <td>
                                                <a href="{{ route('company_priceplan.history', $user->organizations->id) }}" class="btn btn-info btn-sm">View History</a>
                                            </td>
                                        @endif
                                        */ ?>
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