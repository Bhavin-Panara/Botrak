@extends('layouts.newstyle')

@section('title', 'Edit Price Plans')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Price Plans</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('price_plans.index') }}">Price Plans List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Price Plans</li>
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
                    <form action="{{ route('price_plans.update', $plan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="col-md-6 pe-2">
                                    <div class="mb-3">
                                        <label class="form-label">Plan Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $plan->name) }}" placeholder="Plan Name"/>
                                        @error('name')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 ps-2">
                                    <div class="mb-3">
                                        <label class="form-label">Is Unlimited?<span class="text-danger"> *</span></label>
                                        <select name="is_unlimited" class="form-control" id="is_unlimited">
                                            <option value="" {{ old('is_unlimited', $plan->is_unlimited) == '' ? 'selected' : '' }}>Select Plan Limite</option>
                                            <option value="0" {{ old('is_unlimited', $plan->is_unlimited) == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('is_unlimited', $plan->is_unlimited) == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('is_unlimited')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="col-md-6 pe-2">
                                    <div class="mb-3">
                                        <label class="form-label">Plan Type<span class="text-danger"> *</span></label>
                                        <select name="plan_type" class="form-control">
                                            <option value="" {{ old('plan_type', $plan->plan_type) == '' ? 'selected' : '' }}>Select Plan Type</option>
                                            <option value="monthly" {{ old('plan_type', $plan->plan_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="yearly" {{ old('plan_type', $plan->plan_type) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        </select>
                                        @error('plan_type')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 ps-2" id="per_asset_price_field" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">Per Asset Price<span class="text-danger"> *</span></label>
                                        <input type="number" name="per_asset_price" class="form-control" value="{{ old('per_asset_price', $plan->per_asset_price) }}" placeholder="Per Asset Price"/>
                                        @error('per_asset_price')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @php
                                $tiers = old('tiers', $plan->tiers->toArray() ?: [['start_range' => '', 'end_range' => '', 'price' => '']]);
                            @endphp

                            <div id="tiered_pricing_section" style="display: none;">
                                <div class="card card-info card-outline mb-4">
                                    <div class="card-header">
                                        <div class="card-title">Tiered Plan Pricing</div>
                                    </div>
                                    <div class="card-body">
                                        <div id="tiered_pricing_container">
                                            @foreach ($tiers as $index => $tier)
                                                <div class="row mb-3 align-items-center">
                                                    <div class="col-4">
                                                        <input type="number" name="tiers[{{ $index }}][start_range]" class="form-control" placeholder="Start Range" value="{{ $tier['start_range'] }}">
                                                        @error("tiers.$index.start_range")
                                                            <div class="form-text text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="number" name="tiers[{{ $index }}][end_range]" class="form-control" placeholder="End Range" value="{{ $tier['end_range'] }}">
                                                        @error("tiers.$index.end_range")
                                                            <div class="form-text text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="number" name="tiers[{{ $index }}][price]" class="form-control" placeholder="Price" value="{{ $tier['price'] }}">
                                                        @error("tiers.$index.price")
                                                            <div class="form-text text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    @if ($index > 0)
                                                        <div class="col-1" onclick="removeTierRow(this)">
                                                            <button type="button" class="btn btn-danger btn-sm">✕</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center">
                                        <button type="button" class="btn btn-info" onclick="addTierRow()">+ Add Tier</button>
                                        @error('tiers')
                                            <div class="form-text text-danger mx-4 my-0">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="unlimited_price_field" style="display: {{ $plan->is_unlimited ? 'block' : 'none' }};">
                                <label class="form-label">Unlimited Plan Price<span class="text-danger"> *</span></label>
                                <input type="number" name="unlimited_price" class="form-control" value="{{ old('unlimited_price', $plan->unlimited_price) }}">
                                @error('unlimited_price')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- <div class="mb-3">
                                <label class="form-label">Total Plan Days</label>
                                <input type="number" name="total_days" class="form-control" value="{{ old('total_days', $plan->total_days) }}" placeholder="Total Plan Days"/>
                                @error('total_days')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div> -->
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isUnlimited = document.getElementById('is_unlimited');
        const planType = document.querySelector('select[name="plan_type"]');

        function togglePlanTypeOptions() {
            const unlimitedValue = isUnlimited.value;

            Array.from(planType.options).forEach(option => {
                option.hidden = false;
                option.disabled = false;
            });

            if (unlimitedValue === '0') {
                Array.from(planType.options).forEach(option => {
                    if (option.value === 'yearly') {
                        option.disabled = true;
                    }
                });

                if (planType.value === 'yearly') {
                    planType.value = '';
                }
            }
        }

        isUnlimited.addEventListener('change', togglePlanTypeOptions);

        togglePlanTypeOptions();
    });

    let tierIndex = {{ count($tiers) }};
    togglePriceFields();

    document.getElementById('is_unlimited').addEventListener('change', togglePriceFields);

    function togglePriceFields() {
        var isUnlimited = document.getElementById('is_unlimited').value;

        const tierSection = document.getElementById('tiered_pricing_section');
        const perassetpriceField = document.getElementById('per_asset_price_field');
        const unlimitedField = document.getElementById('unlimited_price_field');
        const tierContainer = document.getElementById('tiered_pricing_container');

        if (isUnlimited == '1') {
            tierSection.style.display = 'none';
            perassetpriceField.style.display = 'none';
            unlimitedField.style.display = 'block';

            // Clear all tier rows
            tierContainer.innerHTML = '';
            tierIndex = 0;

        } else if (isUnlimited == '0') {
            tierSection.style.display = 'block';
            perassetpriceField.style.display = 'block';
            unlimitedField.style.display = 'none';

            // Clear unlimited price field
            unlimitedField.querySelector('input').value = '';

            // Ensure there's at least one row if none exist
            if (tierContainer.children.length === 0) {
                addTierRow();
            }

        } else {
            tierSection.style.display = 'none';
            perassetpriceField.style.display = 'none';
            unlimitedField.style.display = 'none';
        }
    }

    function addTierRow() {
        const container = document.getElementById('tiered_pricing_container');
        const row = document.createElement('div');
        row.className = 'row mb-3 align-items-center';

        row.innerHTML = `
            <div class="col-4"><input type="number" name="tiers[${tierIndex}][start_range]" class="form-control" placeholder="Start Range"></div>
            <div class="col-4"><input type="number" name="tiers[${tierIndex}][end_range]" class="form-control" placeholder="End Range"></div>
            <div class="col-3"><input type="number" name="tiers[${tierIndex}][price]" class="form-control" placeholder="Price"></div>
            ${tierIndex > 0 ? `<div class="col-1" onclick="removeTierRow(this)"><button type="button" class="btn btn-danger btn-sm">✕</button></div>` : ''}
        `;

        container.appendChild(row);
        tierIndex++;
    }

    function removeTierRow(button) {
        button.parentNode.remove();
    }
</script>
@endsection