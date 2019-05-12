<?php

namespace App\Http\Controllers;

use App\Permissions\SalePermissions;
use App\Subscription;
use App\SubscriptionPlan;

class SubscriptionController extends Controller
{
    public function create(int $subscriptionPlanId) : Subscription
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($subscriptionPlanId);
        SalePermissions::createSubscription($subscriptionPlan);
        return Subscription::create([
            'subscription_plan_id' => $subscriptionPlan->id,
        ]);
    }

    public function list() : array
    {
        SalePermissions::viewAllSubscriptions();
        return Subscription::all();
    }

    public function get(int $id) : Subscription
    {
        $subscription = Subscription::findOrFail($id);
        SalePermissions::viewSubscription($subscription);
        return $subscription;
    }
}
