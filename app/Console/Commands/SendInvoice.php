<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompanyPricePlans;
use App\Models\Invoices;
use App\Models\User;
use App\Models\Versions;
use App\Models\OrganizationAssets;
use App\Models\PricePlanTiers;
use App\Models\PricePlans;
use Carbon\Carbon;
use App\Mail\SendInvoiceMail;
use Illuminate\Support\Facades\Mail;
use League\MimeTypeDetection\GeneratedExtensionToMimeTypeMap;

class SendInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description : send invoice mail automatically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today();
        $plans = CompanyPricePlans::with(['organizations', 'priceplan'])->get();
        $plansByCompany = $plans->groupBy('company_id');

        foreach ($plansByCompany as $companyId => $companyPlans) {
            $companyPlans = $companyPlans->sortBy('start_date');

            foreach ($companyPlans as $plan) {
                // Compare start date with today
                if (Carbon::parse($plan->start_date)->isSameDay($today)) {
                    $invoices = Invoices::with(['receiver', 'sender.organizations', 'companypriceplans.priceplan'])
                    ->whereNull('sent_date')
                    ->where('invoice_status', 'generated')
                    ->whereNotNull('invoice_sender_id')
                    ->where('invoice_receiver_id', $plan->company_id)
                    ->where('company_price_plans_id', $plan->id)
                    ->first();

                    if (!is_null($invoices)) {
                        $invoices->update([
                            'sent_date' => now()->format('Y-m-d H:i:s.u'),
                            'invoice_status' => 'sent'
                        ]);

                        Mail::to('bhavin.virtueinfo@gmail.com')->send(new SendInvoiceMail($invoices));
                    }
                }

                // Compare end date with today
                if (Carbon::parse($plan->end_date)->isSameDay($today) && $plan->billing_frequency === "recurring billing") {
                    $price_plans = PricePlans::where('id', $plan->price_plan_id)->first();
                    $start_date = Carbon::parse($plan->end_date)->addDays();
                    $end_date = Carbon::parse($start_date)->addDays($price_plans->total_days - 1);

                    $new_company_priceplans = CompanyPricePlans::create([
                        "company_id" => $plan->company_id,
                        "price_plan_id" => $plan->price_plan_id,
                        "start_date" => $start_date,
                        "end_date" => $end_date,
                        "billing_frequency" => $plan->billing_frequency,
                    ]);

                    if ($new_company_priceplans->exists) {
                        $organization_user = User::where('recent_organization_id', $new_company_priceplans->company_id)->pluck('id')->unique();
                        $asset_ids = Versions::where('item_type', 'OrganizationAsset')->whereIn('whodunnit', $organization_user)->pluck('item_id')->unique();

                        $asset_counts = OrganizationAssets::whereIn('id', $asset_ids)->count();
                        $price_plan_details = PricePlans::with('tiers')->where('id', $new_company_priceplans->price_plan_id)->first();

                        $invoice_time = now()->format('jnYHis');
                        $invoice_number = "INV".$invoice_time.$new_company_priceplans->company_id;

                        $invoice_details = [];
                        $invoice_details['sent_date'] = null;
                        $invoice_details['invoice_status'] = "generated";

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

                        Invoices::create([
                            'invoice_number' => $invoice_number,
                            'generate_date' => now()->format('Y-m-d H:i:s.u'),
                            'sent_date' => $invoice_details['sent_date'],
                            'invoice_status' => $invoice_details['invoice_status'],
                            'invoice_sender_id' => 1,
                            'invoice_receiver_id' => $new_company_priceplans->company_id,
                            'company_price_plans_id' => $new_company_priceplans->id,
                            'plan_start_date' => $new_company_priceplans->start_date,
                            'plan_end_date' => $new_company_priceplans->end_date,
                            'amount' => $invoice_details['total_amount'] ?? 0,
                            'discount' => 0,
                            'total_amount' => $invoice_details['total_amount'] ?? 0,
                            'payment_status' => 'pending',
                        ]);
                    }
                }

                // Compare day after end date with today
                // if (Carbon::parse($plan->end_date)->addDay()->isSameDay($today)) {}
            }
        }

        $this->info("Mail Send successfully.");
    }
}
