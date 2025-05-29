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
                                    <th>Billing Frequency</th>
                                    <!-- <th>History</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="align-middle">
                                        <td>{{ $user->organizations->name }}</td>
                                        <td>{{ $user->priceplan->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user->start_date)->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user->end_date)->format('d/m/Y') ?? '-' }}</td>
                                        @php
                                            $status_color = [
                                                'completed' => 'green',
                                                'continue' => 'orange',
                                                'next'     => 'blue'
                                            ];
                                            $status_bgcolor = $status_color[$user->status] ?? '';
                                        @endphp
                                        <td class="text-nowrap"><span class="badge" style="background-color: {{ $status_bgcolor }};">{{ $user->status }}</span></td>
                                        @php
                                            $billing_color = [
                                                'one time billing' => 'blue',
                                                'recurring billing' => 'green',
                                            ];
                                            $billing_bgcolor = $billing_color[$user->billing_frequency] ?? '';
                                        @endphp
                                        <td class="text-nowrap">
                                            <span class="badge" style="background-color: {{ $billing_bgcolor }}; cursor:pointer;"
                                                onclick="openModal({{ $user->id }}, '{{ $user->billing_frequency }}')">
                                                {{ $user->billing_frequency }}
                                            </span>
                                        </td>

                                        <!-- <td>
                                            <a href="{{ route('company_priceplan.history', $user->organizations->id) }}" class="btn btn-info btn-sm">View History</a>
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

<!--begin::Modal-->
<div class="modal fade" id="billingModal" tabindex="-1" aria-labelledby="billingModalLabel" aria-hidden="true">
    <!--begin::Modal Dialog-->
    <div class="modal-dialog">
        <!--begin::Modal Form-->
        <form id="billingForm" method="POST" action="{{ route('company_priceplan.update_billing') }}">
            @csrf

            <input type="hidden" name="user_id" id="modal_user_id" value="{{ old('user_id') }}">
            <!--begin::Modal Content-->
            <div class="modal-content" style="border-top: 3px solid blue;">
                <!--begin::Modal Header-->
                <div class="modal-header">
                    <h5 class="modal-title" id="billingModalLabel">Change Billing Frequency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!--end::Modal Header-->
                <!--begin::Modal Body-->
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Billing Frequency<span class="text-danger"> *</span></label>
                        <fieldset class="d-flex align-items-center">
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="billing_frequency" id="one_time_billing" value="one time billing" {{ old('billing_frequency') == 'one time billing' ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="one_time_billing"> One Time Billing </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="billing_frequency" id="recurring_billing" value="recurring billing" {{ old('billing_frequency') == 'recurring billing' ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="recurring_billing"> Recurring Billing </label>
                                </div>
                            </div>
                        </fieldset>
                        @if ($errors->has('billing_frequency') || $errors->has('user_id'))
                            <div id="validationcnError" class="form-text text-danger">
                                {!! $errors->first('billing_frequency') . '<br>' . $errors->first('user_id') !!}
                            </div>
                        @endif
                        @if ($errors->any())
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                const modal = new bootstrap.Modal(document.getElementById('billingModal'));
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
                    <button type="submit" class="btn btn-primary">Update</button>
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
    function openModal(userId, currentValue) {
        document.getElementById('modal_user_id').value = userId;
        document.getElementById('one_time_billing').checked = currentValue === 'one time billing';
        document.getElementById('recurring_billing').checked = currentValue === 'recurring billing';
        document.getElementById('validationError').style.display = 'none';
        new bootstrap.Modal(document.getElementById('billingModal')).show();
    }

    document.getElementById('billingForm').addEventListener('submit', function (e) {
        const radios = document.getElementsByName('billing_frequency');
        let selected = false;
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                selected = true;
                break;
            }
        }
        if (!selected) {
            e.preventDefault();
            document.getElementById('validationcnError').style.display = 'none';
            document.getElementById('validationError').textContent = 'Billing frequency is required.';
            document.getElementById('validationError').style.display = 'block';
        }
    });
</script>
@endsection