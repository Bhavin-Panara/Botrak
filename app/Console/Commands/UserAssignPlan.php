<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompanyPricePlans;
use App\Models\Organizations;
use Carbon\Carbon;

class UserAssignPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:user_assign_plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description : Assign plan to user automatically';

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
        $plans = CompanyPricePlans::with(['organizations', 'priceplan'])->where(function ($query) { $query->where('status', '!=', 'cancel')->orWhereNull('status'); })->get();
        $plansByCompany = $plans->groupBy('company_id');

        foreach ($plansByCompany as $companyId => $companyPlans) {
            $companyPlans = $companyPlans->sortBy('start_date');
            $updatedOrg = false;
            $hasActivePlan = false;

            foreach ($companyPlans as $plan) {
                $status = null;

                if ($plan->end_date && Carbon::parse($plan->end_date)->lt($today)) {
                    $status = 'completed';
                } elseif ($plan->start_date <= $today && (!$plan->end_date || $plan->end_date >= $today)) {
                    $status = 'continue';
                    $hasActivePlan = true;
                } elseif ($plan->start_date > $today) {
                    $status = 'next';
                }

                $plan->status = $status;
                $plan->save();

                if ($status === 'continue' && !$updatedOrg) {
                    Organizations::where('id', $companyId)->update([
                        'subscription_id' => $plan->price_plan_id,
                        'start_date' => $plan->start_date,
                        'end_date' => $plan->end_date
                    ]);
                    $updatedOrg = true;
                }
            }

            if (!$hasActivePlan) {
                Organizations::where('id', $companyId)->update([
                    'subscription_id' => null,
                    'start_date' => null,
                    'end_date' => null
                ]);
            }
        }

        $this->info("Plan statuses updated successfully.");
    }
}