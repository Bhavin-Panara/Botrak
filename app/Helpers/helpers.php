<?php

use App\Models\Invoices;
use App\Models\Organizations;
use App\Models\User;
use App\Models\Versions;
use App\Models\OrganizationAssets;
use App\Models\PricePlanTiers;
use App\Mail\SendInvoiceMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

/**
 * Write code for one time send invoice in email
 *
 * @return response()
 */
function OneTimeInvoiceEmail($plan_data)
{
    $invoiceExists = Invoices::where('company_price_plans_id', $plan_data->id)->exists();

    if (!$invoiceExists) {
        $startDate = Carbon::parse($plan_data->start_date)->format('d');
        $startMonth = Carbon::parse($plan_data->start_date)->format('m');
        $endDate = Carbon::parse($plan_data->end_date)->format('d');
        $endMonth = Carbon::parse($plan_data->end_date)->format('m');
        $invoice_number = "INV".$startDate.$startMonth.$endDate.$endMonth.$plan_data->company_id;

        $senderId = Auth::check() ? Auth::id() : 1;

        if (!$plan_data->priceplan->is_unlimited) {
            $organization_user = User::where('recent_organization_id', $plan_data->company_id)->pluck('id')->unique();
            $assetIds = Versions::where('item_type', 'OrganizationAsset')->whereIn('whodunnit', $organization_user)->pluck('item_id')->unique();
            $assetCounts = OrganizationAssets::whereIn('id', $assetIds)->count();

            $extraAssetRate = $plan_data->priceplan->per_asset_price;
            
            if ($assetCounts > 0) {
                $price_paln_tiers = PricePlanTiers::where('price_plan_id', $plan_data->priceplan->id)->orderBy('start_range')->get();

                $matchedTier = $price_paln_tiers->where('start_range', '<=', $assetCounts)->where('end_range', '>=', $assetCounts)->first();

                if ($matchedTier) {
                    $totalAmount = $matchedTier->price;
                } else {
                    $maxTier = $price_paln_tiers->sortByDesc('end_range')->first();
                    if ($maxTier && $assetCounts > $maxTier->end_range) {
                        $totalAmount = $maxTier->price;
                        $extraAssets = $assetCounts - $maxTier->end_range;
                        $extraAssetCharge = $extraAssets * $extraAssetRate;
                        $totalAmount += $extraAssetCharge;
                    }
                }
            } else {
                $price_paln_tiers = PricePlanTiers::where('price_plan_id', $plan_data->priceplan->id)->orderBy('start_range')->get();

                $maxTier = $price_paln_tiers->sortByDesc('end_range')->first();

                $totalAmount = $maxTier->price;
            }
        } else {
            $totalAmount = $plan_data->priceplan->unlimited_price;
        }

        $invoice = Invoices::create([
            'invoice_number' => $invoice_number,
            'generate_date' => now()->format('Y-m-d H:i:s.u'),
            'sent_date' => now()->format('Y-m-d H:i:s.u'),
            'invoice_status' => 'sent',
            'invoice_sender_id' => $senderId,
            'invoice_receiver_id' => $plan_data->company_id,
            'company_price_plans_id' => $plan_data->id,
            'amount' => $totalAmount,
            'discount' => 0,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
        ]);

        $organization = Organizations::find($plan_data->company_id);
        if ($organization && $organization->organization_email) {
            Mail::to('bhavin.virtueinfo@gmail.com')->send(new SendInvoiceMail($invoice));
        }
    }
}

/**
 * Write code for recurring send invoice in email
 *
 * @return response()
 */
function RecurringInvoiceEmail($plan_data)
{
    $senderId = Auth::check() ? Auth::id() : 1;
    $startDate = Carbon::parse($plan_data->start_date)->startOfMonth();
    $endDate = $plan_data->end_date ? Carbon::parse($plan_data->end_date)->endOfMonth() : Carbon::now()->endOfMonth();
    $currentMonth = Carbon::now()->startOfMonth();

    // Iterate from start month to current month (or endDate if exists)
    while ($startDate->lte($currentMonth) && $startDate->lte($endDate)) {
        // Skip if invoice for that month already exists
        $invoiceExists = Invoices::where('company_price_plans_id', $plan_data->id)
            ->whereYear('generate_date', $startDate->year)
            ->whereMonth('generate_date', $startDate->month)
            ->exists();

        if (!$invoiceExists) {
            // === Calculate Asset-Based Pricing ===
            $organization_user = User::where('recent_organization_id', $plan_data->company_id)->pluck('id')->unique();
            $assetIds = Versions::where('item_type', 'OrganizationAsset')->whereIn('whodunnit', $organization_user)->pluck('item_id')->unique();
            $assetCounts = OrganizationAssets::whereIn('id', $assetIds)->count();

            $extraAssetRate = $plan_data->priceplan->per_asset_price;
            $totalAmount = 0;

            if (!$plan_data->priceplan->is_unlimited) {
                $tiers = PricePlanTiers::where('price_plan_id', $plan_data->priceplan->id)->orderBy('start_range')->get();
                $matchedTier = $tiers->where('start_range', '<=', $assetCounts)->where('end_range', '>=', $assetCounts)->first();

                if ($matchedTier) {
                    $totalAmount = $matchedTier->price;
                } else {
                    $maxTier = $tiers->sortByDesc('end_range')->first();
                    $totalAmount = $maxTier->price ?? 0;

                    if ($assetCounts > $maxTier->end_range) {
                        $extraAssets = $assetCounts - $maxTier->end_range;
                        $totalAmount += ($extraAssets * $extraAssetRate);
                    }
                }
            } else {
                $totalAmount = $plan_data->priceplan->unlimited_price;
            }

            $startDate = Carbon::parse($plan_data->start_date)->format('d');
            $startMonth = Carbon::parse($plan_data->start_date)->format('m');
            $endDate = Carbon::parse($plan_data->end_date)->format('d');
            $endMonth = Carbon::parse($plan_data->end_date)->format('m');
            $invoice_number = "INV".$startDate.$startMonth.$endDate.$endMonth.$plan_data->company_id;

            $invoice = Invoices::create([
                'invoice_number' => $invoice_number,
                'generate_date' => $plan_data->start_date->format('Y-m-d H:i:s.u'),
                'sent_date' => now()->format('Y-m-d H:i:s.u'),
                'invoice_status' => 'sent',
                'invoice_sender_id' => $senderId,
                'invoice_receiver_id' => $plan_data->company_id,
                'company_price_plans_id' => $plan_data->id,
                'amount' => $totalAmount,
                'discount' => 0,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
            ]);

            $organization = Organizations::find($plan_data->company_id);
            if ($organization && $organization->organization_email) {
                Mail::to('bhavin.virtueinfo@gmail.com')->send(new SendInvoiceMail($invoice));
            }
        }

        // Next Month
        $startDate->addMonth();
    }
}