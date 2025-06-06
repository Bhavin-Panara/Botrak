<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\Invoices;
use Illuminate\Http\Request;

class InvoiceController extends BaseController
{
    public function user_plan_invoice(Request $request)
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

            $user_invoice = Invoices::with(['receiver', 'sender.organizations', 'companypriceplans.priceplan'])->where('invoice_receiver_id', $request->id)->get();

            if (!$user_invoice) {
                return $this->sendError('User organization plan invoice not found.');
            }

            return $this->sendResponse($user_invoice, 'User organization plan invoice successfully.');
        } else {
            return $this->sendError('Unauthorized access.');
        }
    }
}