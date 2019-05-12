<?php

namespace App\Http\Controllers;

use App\Permissions\SalePermissions;
use App\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function create(Request $request) : SubscriptionPlan
    {
        SalePermissions::createSubscriptionPlan();
        return SubscriptionPlan::create($request->all());
    }

    public function list() : array
    {
        SalePermissions::viewAllSubscriptionPlans();
        return SubscriptionPlan::all();
    }

    public function get(int $id) : SubscriptionPlan
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);
        SalePermissions::viewSubscriptionPlan($subscriptionPlan);
        return $subscriptionPlan;
    }

    public function edit(Request $request, int $id) : SubscriptionPlan
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);
        SalePermissions::editSubscriptionPlan($subscriptionPlan);
        $subscriptionPlan->update($request->all());
        return $subscriptionPlan;
    }

    public function enable(int $id) : SubscriptionPlan
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);
        SalePermissions::toggleSubscriptionPlan();
        $subscriptionPlan->update(['disabled_at' => null]);
        return $subscriptionPlan;
    }

    public function disable(int $id) : SubscriptionPlan
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);
        SalePermissions::toggleSubscriptionPlan();
        $subscriptionPlan->update(['disabled_at' => Carbon::now()]);
        return $subscriptionPlan;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id) : bool
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);
        SalePermissions::deleteSubscriptionPlan($subscriptionPlan);
        $subscriptionPlan->delete();
        return true;
    }
}
