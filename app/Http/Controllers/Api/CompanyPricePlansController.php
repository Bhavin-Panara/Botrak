<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\CompanyPricePlans;
use App\Models\User;
use App\Models\Organizations;
use App\Models\Invoices;
use Illuminate\Http\Request;
use App\Mail\PlanCanceldMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class CompanyPricePlansController extends BaseController
{
    public function user_plan_details(Request $request)
    {
        if ($request->has(['id', 'authentication_token'])) {
            $user = User::with(['roles', 'organizations'])->where('authentication_token', $request->authentication_token)->whereHas('organizations', function ($query) use ($request) {
                $query->where('id', $request->id);
            })->first();

            if (!$user) { return $this->sendError('User not found.'); }

            if ($user->roles->isEmpty()) { return $this->sendError('User has no assigned role.'); }

            if ($user->roles->first()->name !== "organization_super_admin") {
                return $this->sendError('You must be an organization super admin to perform this action');
            }

            $user_plan = CompanyPricePlans::with(['organizations', 'priceplan'])->where('status', 'continue')->where('company_id', $request->id)->first();

            if (!$user_plan) {
                return $this->sendError('Company Price Plan not found.');
            }

            return $this->sendResponse($user_plan, 'User organization plan successfully.');
        } else {
            return $this->sendError('Unauthorized access.');
        }
    }

    public function cancel_plan(Request $request)
    {
        if ($request->has(['id', 'authentication_token', 'company_plan_id'])) {
            $user = User::with(['roles', 'organizations'])->where('authentication_token', $request->authentication_token)->whereHas('organizations', function ($query) use ($request) {
                $query->where('id', $request->id);
            })->first();

            if (!$user) { return $this->sendError('User not found.'); }

            if ($user->roles->isEmpty()) { return $this->sendError('User has no assigned role.'); }

            if ($user->roles->first()->name !== "organization_super_admin") {
                return $this->sendError('You must be an organization super admin to perform this action');
            }

            $priceplans = CompanyPricePlans::with(['organizations', 'priceplan'])->where('status', '!=', 'cancel')->where('id', $request->company_plan_id)->first();

            if(!$priceplans) {
                return $this->sendError('Organization plan is invalid.');
            }

            $organization_user = Organizations::where('id', $priceplans->company_id)->first();
            if ($organization_user) {
                $organization_user->subscription_id = null;
                $organization_user->start_date = null;
                $organization_user->end_date = null;
                $organization_user->update();
            }

            $invoice = Invoices::where('invoice_receiver_id', $priceplans->company_id)->where('company_price_plans_id', $priceplans->id)->where('plan_start_date', $priceplans->start_date)->where('plan_end_date', $priceplans->end_date)->first();
            if($invoice){
                $invoice->invoice_status = 'cancel';
                $invoice->update();
            }

            $priceplans->status = 'cancel';
            $priceplans->save();

            if ($organization_user && $organization_user->organization_email) {
                Mail::to('bhavin.virtueinfo@gmail.com')->send(new PlanCanceldMail($priceplans));
            }

            return $this->sendResponse($priceplans, 'User organization plan cancel successfully.');
        } else {
            return $this->sendError('Unauthorized access.');
        }
    }
}