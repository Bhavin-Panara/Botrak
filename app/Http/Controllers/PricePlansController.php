<?php
namespace App\Http\Controllers;

use App\Models\PricePlans;
use Illuminate\Http\Request;

class PricePlansController extends Controller
{
    public function index()
    {
        $plans = PricePlans::get();
        return view('price_plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'plan_type' => 'required|in:monthly,yearly',
            'is_unlimited' => 'required|in:0,1',
            // 'per_asset_price' => 'required|numeric|min:0',
            'per_asset_price' => 'nullable|numeric|min:1|required_if:is_unlimited,0',
            // 'total_days' => 'nullable|numeric',
            'unlimited_price' => 'nullable|numeric|required_if:is_unlimited,1',
            'tiers' => 'nullable|array|required_if:is_unlimited,0|min:1',
            'tiers.*.start_range' => 'nullable|integer|min:0|required_if:is_unlimited,0',
            'tiers.*.end_range' => 'nullable|integer|min:0|required_if:is_unlimited,0|gt:tiers.*.start_range',
            'tiers.*.price' => 'nullable|numeric|min:0|required_if:is_unlimited,0',
        ],[
            'per_asset_price.required_if' => 'Per Asset Price is required when the plan is not unlimited.',
            'unlimited_price.required_if' => 'Unlimited price is required when the plan is unlimited.',
            'tiers.required_if' => 'Tiered pricing is required when the plan is not unlimited.',
            'tiers.*.start_range.required_if' => 'The start range is required for each tier.',
            'tiers.*.start_range.integer' => 'The start range must be an integer.',
            'tiers.*.start_range.min' => 'The start range must be at least 0.',
            'tiers.*.end_range.required_if' => 'The end range is required for each tier.',
            'tiers.*.end_range.integer' => 'The end range must be an integer.',
            'tiers.*.end_range.min' => 'The end range must be at least 0.',
            'tiers.*.end_range.gt' => 'The end range must be greater than the start range.',
            'tiers.*.price.required_if' => 'The price is required for each tier.',
            'tiers.*.price.numeric' => 'The price must be a number.',
            'tiers.*.price.min' => 'The price must be at least 0.',
        ]);

        if(!isset($request->total_days)) {
            $validated['total_days'] = $validated['plan_type'] === "monthly" ? "31" : ($validated['plan_type'] === "yearly" ? "365" : null);
        }

        $pricePlan = PricePlans::create([
            'name' => $validated['name'],
            'plan_type' => $validated['plan_type'],
            'is_unlimited' => $validated['is_unlimited'],
            'unlimited_price' => $validated['unlimited_price'],
            'per_asset_price' => $validated['per_asset_price'],
            'total_days' => $validated['total_days'],
        ]);

        if (!$pricePlan->is_unlimited && !empty($validated['tiers'])) {
            foreach ($validated['tiers'] as $tier) {
                $pricePlan->tiers()->create([
                    'start_range' => $tier['start_range'],
                    'end_range' => $tier['end_range'],
                    'price' => $tier['price'],
                ]);
            }
        }

        return redirect()->route('price_plans.index')->with('success', 'Price plan created successfully.');
    }

    public function edit($id)
    {
        $plan = PricePlans::where('id', $id)->first();
        return view('price_plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $isUnlimited = $request->input('is_unlimited');

        $rules = [
            'name' => 'required|string|max:255',
            'plan_type' => 'required|in:monthly,yearly',
            'is_unlimited' => 'required|in:0,1',
            // 'total_days' => 'nullable|numeric|min:1',
        ];

        if ($isUnlimited) {
            $rules['unlimited_price'] = 'required|numeric|min:0';
        } else {
            $rules['per_asset_price'] = 'required|numeric|min:1';
            $rules['tiers'] = 'required|array|min:1';
            $rules['tiers.*.start_range'] = 'required|integer|min:0';
            $rules['tiers.*.end_range'] = 'required|integer|min:0|gt:tiers.*.start_range';
            $rules['tiers.*.price'] = 'required|numeric|min:0';
        }

        $messages = [
            'per_asset_price.required' => 'Per Asset Price is required when the plan is not unlimited.',
            'unlimited_price.required' => 'Unlimited price is required when the plan is unlimited.',
            'tiers.required' => 'Tiered pricing is required when the plan is not unlimited.',
            'tiers.*.start_range.required' => 'The start range is required for each tier.',
            'tiers.*.start_range.integer' => 'The start range must be an integer.',
            'tiers.*.start_range.min' => 'The start range must be at least 0.',
            'tiers.*.end_range.required' => 'The end range is required for each tier.',
            'tiers.*.end_range.integer' => 'The end range must be an integer.',
            'tiers.*.end_range.min' => 'The end range must be at least 0.',
            'tiers.*.end_range.gt' => 'The end range must be greater than the start range.',
            'tiers.*.price.required' => 'The price is required for each tier.',
            'tiers.*.price.numeric' => 'The price must be a number.',
            'tiers.*.price.min' => 'The price must be at least 0.',
        ];

        $validated = $request->validate($rules, $messages);

        if(!isset($request->total_days)) {
            $validated['total_days'] = $validated['plan_type'] === "monthly" ? "31" : ($validated['plan_type'] === "yearly" ? "365" : null);
        }

        $pricePlan = PricePlans::findOrFail($id);

        $pricePlan->update([
            'name' => $validated['name'],
            'plan_type' => $validated['plan_type'],
            'is_unlimited' => $validated['is_unlimited'],
            'unlimited_price' => $validated['unlimited_price'] ?? null,
            'per_asset_price' => $validated['per_asset_price'] ?? null,
            'total_days' => $validated['total_days'],
        ]);

        if (!$pricePlan->is_unlimited) {
            $pricePlan->tiers()->delete();

            if (!empty($validated['tiers'])) {
                foreach ($validated['tiers'] as $tier) {
                    $pricePlan->tiers()->create([
                        'start_range' => $tier['start_range'],
                        'end_range' => $tier['end_range'],
                        'price' => $tier['price'],
                    ]);
                }
            }
        } else {
            $pricePlan->tiers()->delete();
        }

        return redirect()->route('price_plans.index')->with('success', 'Price plan updated successfully.');
    }

    public function destroy($id)
    {
        $pricePlan = PricePlans::where('id', $id)->first();
        $pricePlan->tiers()->delete();
        $pricePlan->delete();

        return redirect()->route('price_plans.index')->with('success', 'Plan deleted successfully.');
    }
}