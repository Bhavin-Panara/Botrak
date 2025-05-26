<?php

namespace App\Http\Controllers;

use App\Models\CompanyPricePlans;
use App\Models\UsersRoles;
use App\Models\Organizations;
use App\Models\OrganizationAssets;
use App\Models\Versions;
use App\Models\PricePlans;
use App\Models\PricePlanTiers;
use App\Models\User;
use App\Models\Invoices;
use Carbon\Carbon;
use PDF;
use App\Mail\SendInvoiceMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompanyPricePlansController extends Controller
{
    public function index()
    {
        // $users = CompanyPricePlans::with(['organizations', 'priceplan'])->whereIn('status', ['completed', 'continue'])->get()->unique('company_id');
        $users = CompanyPricePlans::with(['organizations', 'priceplan'])->get();
        return view('company_priceplan.index', compact('users'));
    }

    public function history($id)
    {
        $assign_plans = CompanyPricePlans::with(['organizations', 'priceplan'])->where('company_id', $id)->where('status', 'completed')->get();
        return view('company_priceplan.history', compact('assign_plans'));
    }
    
    public function get_plan_details($id)
    {
        $plan = PricePlans::with('tiers')->find($id);

        if (!$plan) {
            return response()->json(['error' => 'Price plan not found.'], 404);
        }

        $data = [
            'name' => $plan->name,
            'plan_type' => ucfirst($plan->plan_type),
            'is_unlimited' => $plan->is_unlimited ? 'Yes' : 'No',
            'unlimited_price' => $plan->unlimited_price,
            'per_asset_price' => $plan->per_asset_price,
            'total_days' => $plan->total_days,
            'tiers' => $plan->tiers,
        ];
    
        return response()->json(['data' => $data]);
    }

    public function create()
    {
        $users = Organizations::get();
        $plans = PricePlans::all();

        $assetCounts = [];
        foreach ($users as $user) {
            $organization_user = User::where('recent_organization_id', $user->id)->pluck('id')->unique();

            $assetIds = Versions::where('item_type', 'OrganizationAsset')->whereIn('whodunnit', $organization_user)->pluck('item_id')->unique();

            $assetCounts[$user->id] = OrganizationAssets::whereIn('id', $assetIds)->count();
        }

        return view('company_priceplan.create', compact('users', 'plans', 'assetCounts'));
    }

    public function store(Request $request)
    {
        $check_future_plan = CompanyPricePlans::where('company_id', $request->company_id)->where('billing_frequency', 'recurring billing')->whereDate('start_date', '>=', Carbon::today())->first();

        if ($check_future_plan) {
            return redirect()->back()->withInput()->with('future_plan_warning', true)
                ->with('future_plan_start_date', $check_future_plan->start_date)
                ->with('future_plan_end_date', $check_future_plan->end_date);
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:organizations,id',
            'price_plan_id' => 'required|exists:price_plans,id',
            'start_date' => 'required|date|after_or_equal:today',
            'billing_frequency' => 'required|in:one time billing,recurring billing',
        ],[
            'company_id.required' => 'The organization field is required',
            'company_id.exists' => 'The selected organization does not exist.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $plan = PricePlans::find($request->price_plan_id);
            if (!$plan) {
                $validator->errors()->add('price_plan_id', 'Invalid plan selected.');
                return;
            }

            $startDate = Carbon::parse($request->start_date);
            $endDate = $startDate->copy()->addDays($plan->total_days - 1);

            $conflict = CompanyPricePlans::where('company_id', $request->company_id)->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])->orWhereBetween('end_date', [$startDate, $endDate])->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)->where('end_date', '>=', $endDate);
                });
            })->exists();

            if ($conflict) {
                $validator->errors()->add('start_date', 'Plan is already assigned during this time period!');
            }
        });

        $validated = $validator->validate();

        $price_plans = PricePlans::where('id', $validated['price_plan_id'])->first();
        $validated['end_date'] = Carbon::parse($validated['start_date'])->addDays($price_plans->total_days - 1)->toDateString();

        $company_priceplans = CompanyPricePlans::create($validated);

        if ($company_priceplans->exists) {
            $organization_user = User::where('recent_organization_id', $company_priceplans->company_id)->pluck('id')->unique();
            $asset_ids = Versions::where('item_type', 'OrganizationAsset')->whereIn('whodunnit', $organization_user)->pluck('item_id')->unique();

            $asset_counts = OrganizationAssets::whereIn('id', $asset_ids)->count();
            $price_plan_details = PricePlans::with('tiers')->where('id', $company_priceplans->price_plan_id)->first();

            $invoice_time = now()->format('jnYHis');
            $invoice_number = "INV".$invoice_time.$company_priceplans->company_id;

            $invoice_details = [];

            $startDate = Carbon::parse($company_priceplans->start_date);
            if ($startDate->isToday()) {
                $invoice_details['sent_date'] = now()->format('Y-m-d H:i:s.u');
                $invoice_details['invoice_status'] = "sent";
            } elseif ($startDate->isFuture()) {
                $invoice_details['sent_date'] = null;
                $invoice_details['invoice_status'] = "generated";
            }

            if (!$price_plan_details->is_unlimited) {
                $extraAssetRate = $price_plan_details->per_asset_price;

                if ($asset_counts > 0) {
                    $price_paln_tiers = PricePlanTiers::where('price_plan_id', $price_plan_details->id)->orderBy('start_range')->get();

                    $matchedTier = $price_paln_tiers->where('start_range', '<=', $asset_counts)->where('end_range', '>=', $asset_counts)->first();

                    if ($matchedTier) {
                        $invoice_details['total_amount'] = $matchedTier->price;
                    } else {
                        $maxTier = $price_paln_tiers->sortByDesc('end_range')->first();
                        if ($maxTier && $asset_counts > $maxTier->end_range) {
                            $totalAmount = $maxTier->price;
                            $extraAssets = $asset_counts - $maxTier->end_range;
                            $extraAssetCharge = $extraAssets * $extraAssetRate;
                            $totalAmount += $extraAssetCharge;
                            $invoice_details['total_amount'] = $totalAmount;
                        }
                    }
                } else {
                    $price_paln_tiers = PricePlanTiers::where('price_plan_id', $price_plan_details->id)->orderBy('start_range')->get();
                    $maxTier = $price_paln_tiers->sortByDesc('end_range')->first();

                    $invoice_details['total_amount'] = $maxTier->price;
                }
            } else {
                $invoice_details['total_amount'] = $price_plan_details->unlimited_price;
            }

            $invoice = Invoices::create([
                'invoice_number' => $invoice_number,
                'generate_date' => now()->format('Y-m-d H:i:s.u'),
                'sent_date' => $invoice_details['sent_date'],
                'invoice_status' => $invoice_details['invoice_status'],
                'invoice_sender_id' => Auth::check() ? Auth::id() : 1,
                'invoice_receiver_id' => $company_priceplans->company_id,
                'company_price_plans_id' => $company_priceplans->id,
                'plan_start_date' => $company_priceplans->start_date,
                'plan_end_date' => $company_priceplans->end_date,
                'amount' => $invoice_details['total_amount'] ?? 0,
                'discount' => 0,
                'total_amount' => $invoice_details['total_amount'] ?? 0,
                'payment_status' => 'pending',
            ]);

            if ($startDate->isToday()) {
                $invoice->load(['sender', 'receiver', 'companypriceplans', 'sender.organizations', 'companypriceplans.priceplan']);
                Mail::to('bhavin.virtueinfo@gmail.com')->send(new SendInvoiceMail($invoice));
            }
        }

        return redirect()->route('company_priceplan.index')->with('success', 'Price plan assign successfully.');
    }

    public function edit($id)
    {
        $user_priceplan = CompanyPricePlans::where('id', $id)->first();
        $users = Organizations::get();
        $plans = PricePlans::all();

        $assetCounts = [];
        foreach ($users as $user) {
            $organization_user = User::where('recent_organization_id', $user->id)->pluck('id')->unique();

            $assetIds = Versions::where('item_type', 'OrganizationAsset')->whereIn('whodunnit', $organization_user)->pluck('item_id')->unique();

            $assetCounts[$user->id] = OrganizationAssets::whereIn('id', $assetIds)->count();
        }

        return view('company_priceplan.edit', compact('user_priceplan', 'users', 'plans', 'assetCounts'));
    }

    public function update(Request $request, $id)
    {
        $check_future_plan = CompanyPricePlans::where('company_id', $request->company_id)->where('billing_frequency', 'recurring billing')->whereDate('start_date', '>', Carbon::today())->first();

        if ($check_future_plan) {
            return redirect()->back()
                ->withInput()
                ->with('future_plan_warning', true)
                ->with('future_plan_start_date', $check_future_plan->start_date)
                ->with('future_plan_end_date', $check_future_plan->end_date);
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:organizations,id',
            'price_plan_id' => 'required|exists:price_plans,id',
            // 'start_date' => 'required|date|after_or_equal:today',
            'start_date' => 'required|date',
            'billing_frequency' => 'required|in:one time billing,recurring billing',
        ],[
            'company_id.required' => 'The organization field is required',
            'company_id.exists' => 'The selected organization does not exist.',
        ]);

        $validator->after(function ($validator) use ($request, $id) {
            $plan = PricePlans::find($request->price_plan_id);
            if (!$plan) {
                $validator->errors()->add('price_plan_id', 'Invalid plan selected.');
                return;
            }

            $startDate = Carbon::parse($request->start_date);
            $endDate = $startDate->copy()->addDays($plan->total_days);

            $existing = CompanyPricePlans::find($id);
            if ($startDate->lt(Carbon::today()) && $startDate->toDateString() !== Carbon::parse(optional($existing)->start_date)->toDateString()) {
                $validator->errors()->add('start_date', 'You cannot set a new past start date.');
            }

            $conflict = CompanyPricePlans::where('company_id', $request->company_id)->where('id', '!=', $id)->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])->orWhereBetween('end_date', [$startDate, $endDate])->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)->where('end_date', '>=', $endDate);
                });
            })->first();

            if ($conflict) {
                $validator->errors()->add('start_date', 'Plan is already assigned during this time period!');
            }
        });

        $validated = $validator->validate();

        $price_plans = PricePlans::where('id', $validated['price_plan_id'])->first();
        $validated['end_date'] = Carbon::parse($validated['start_date'])->addDays($price_plans->total_days)->toDateString();

        $user_priceplans = CompanyPricePlans::where('id', $id)->first();
        $user_priceplans->update($validated);

        /* $user = User::where('id', $validated['company_id'])->first();
        if ($user) {
            $user->subscription_id = $validated['price_plan_id'];
            $user->start_date = $validated['start_date'];
            $user->end_date = $validated['end_date'];
            $user->update();
        } */

        return redirect()->route('company_priceplan.index')->with('success', 'Company Plan updated successfully.');
    }

    public function destroy($id)
    {
        $user_priceplans = CompanyPricePlans::where('id', $id)->first();

        if($user_priceplans->status === "continue") {
            $user = User::where('id', $user_priceplans->company_id)->first();
            if ($user) {
                $user->subscription_id = null;
                $user->start_date = null;
                $user->end_date = null;
                $user->update();
            }
        }

        $user_priceplans->delete();

        return redirect()->route('company_priceplan.index')->with('success', 'Company Plan deleted successfully.');
    }
}