@extends('layouts.newstyle')

@section('title', 'Edit Company Price Plans')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Organization Price Plans</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('company_priceplan.index') }}">Organization Price Plans List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Organization Price Plans</li>
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
                    <!--begin::Form-->
                    <form action="{{ route('company_priceplan.update', $user_priceplan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="col-md-6 pe-2">
                                    <div class="mb-3">
                                        <label class="form-label">Organization Name<span class="text-danger"> *</span></label>
                                        <select name="company_id" class="form-control">
                                            <option value="" {{ old('company_id') == '' ? 'selected' : '' }}>Select Organization</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('company_id', $user_priceplan->company_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} (Total Asset: {{ $assetCounts[$user->id] ?? 0 }})</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 ps-2">
                                    <div class="mb-3">
                                        <label class="form-label">Price Plan<span class="text-danger"> *</span></label>
                                        <select id="price_plan_id" name="price_plan_id" class="form-control">
                                            <option value="" {{ old('price_plan_id') == '' ? 'selected' : '' }}>Select Price Plan</option>
                                            @foreach($plans as $plan)
                                                <option value="{{ $plan->id }}" {{ old('price_plan_id', $user_priceplan->price_plan_id) == $plan->id ? 'selected' : '' }}>
                                                    {{ $plan->name }}
                                                    @if ($plan->is_unlimited == 1)
                                                        (Unlimited)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('price_plan_id')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="plan_details_section"></div>

                            <div class="mb-3">
                                <label class="form-label">Start Date<span class="text-danger"> *</span></label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', \Carbon\Carbon::parse($user_priceplan->start_date)->format('Y-m-d')) }}">
                                @error('start_date')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--end::Body-->
                        <!--begin::Footer-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Plan</button>
                        </div>
                        <!--end::Footer-->
                    </form>
                    <!--end::Form-->
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

@section('pagescript')
@if(session('future_plan_warning'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Plan Exists in Recurring Billing',
        html: `This Organization already has a future plan assigned from <strong>{{ \Carbon\Carbon::parse(session('future_plan_start_date'))->format('d M Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse(session('future_plan_end_date'))->format('d M Y') }}</strong> in recurring billing mode.`,
        confirmButtonText: 'OK'
    });
</script>
@endif
<script>
    $(document).ready(function () {
        const initialPlanId = $('#price_plan_id').val();
        if (initialPlanId) {
            EditPricePlan(initialPlanId);
        }

        $('#price_plan_id').on('change', function () {
            const selectedPlanId = $(this).val();
            EditPricePlan(selectedPlanId);
        });

        function EditPricePlan(planId) {
            if (planId) {
                $.ajax({
                    url: 'get_plan_details/' + planId,
                    type: 'GET',
                    success: function (response) {
                        if (response.data) {
                            let plan = response.data;

                            console.log(plan);

                            var html = `<div class="card card-primary card-outline border-0 bg-light mb-4">
                            <div class="card-header">
                                <div class="card-title">Pricing Plan Details</div>
                            </div>
                            <div class="card-body">`;

                            html += `<div class="d-flex">
                                <div class="col-md-6 pe-2">
                                    <div class="mb-3">
                                        <div class="form-control" style="background-color: #e9ecef;">Plan Name: <strong>${plan.name}</strong></div>
                                    </div>
                                </div>
                                <div class="col-md-6 ps-2">
                                    <div class="mb-3">
                                        <div class="form-control" style="background-color: #e9ecef;">Plan Type: <strong>${plan.plan_type}</strong></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-control" style="background-color: #e9ecef;">Is Unlimited?: <strong>${plan.is_unlimited}</strong></div>
                            </div>`;

                            if (plan.is_unlimited === "No") {
                                if (plan.tiers && plan.tiers.length > 0) {
                                    html += `<div class="card card-info card-outline border-0 bg-light mb-4">
                                        <div class="card-header">
                                            <div class="card-title">Tiered Plan Pricing</div>
                                        </div>
                                    <div class="card-body">`;

                                    plan.tiers.forEach((tier, i) => {
                                        html += `<div class="row ${i < plan.tiers.length - 1 ? 'mb-3' : ''} align-items-center">
                                            <div class="col-4">
                                                <div class="form-control" style="background-color: #e9ecef;">Start Range: <strong>${tier.start_range}</strong></div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-control" style="background-color: #e9ecef;">End Range: <strong>${tier.end_range}</strong></div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-control" style="background-color: #e9ecef;">Price: <strong>${tier.price}</strong></div>
                                            </div>
                                        </div>`;
                                    });
                                        
                                    html += `</div></div>`;
                                }
                            }

                            if (plan.is_unlimited === "Yes") {
                                html += `<div class="mb-3">
                                    <div class="form-control" style="background-color: #e9ecef;">Unlimited Plan Price: <strong>${plan.unlimited_price}</strong></div>
                                </div>`;
                            }

                            html += `<div class="d-flex">
                                        <div class="col-md-6 pe-2">
                                            <div class="mb-3">
                                                <div class="form-control" style="background-color: #e9ecef;">Per Asset Price: <strong>${plan.per_asset_price}</strong></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ps-2">
                                            <div class="mb-3">
                                                <div class="form-control" style="background-color: #e9ecef;">Total Plan Days: <strong>${plan.total_days}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                            $('#plan_details_section').html(html).show();
                        } else {
                            console.error('Plan data missing from response');
                            $('#plan_details_section').hide();
                        }
                    },
                    error: function (xhr) {
                        console.error('AJAX error:', xhr.responseText);
                        $('#plan_details_section').hide();
                    }
                });
            } else {
                $('#plan_details_section').hide();
            }
        }
    });
</script>
@endsection