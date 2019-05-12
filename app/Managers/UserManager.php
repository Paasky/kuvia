<?php

namespace App\Managers;

use App\Events\User\UserActivated;
use App\Events\User\UserCreated;
use App\Events\User\UserDeleted;
use App\Events\User\UserDisabled;
use App\Events\User\UserLoggedout;
use App\Events\User\UserLoggedoutAll;
use App\Events\User\UserUpdated;
use App\Events\User\UserUpdatedEmail;
use App\Events\User\UserUpdatedPassword;
use App\Events\User\UserUpdatedPhone;
use App\Helper;
use App\Repositories\UserRepository;
use App\SubscriptionPlan;
use App\System;
use App\Traits\EncryptsData;
use App\Traits\SavesDetailedLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;

class UserManager
{
    use EncryptsData, SavesDetailedLog;

    public static function isAdmin(?User $user = null) : bool
    {
        if(!$user){
            return false;
        }
        return $user->is_admin;
    }

    public static function hasActiveSubscription(User $user, SubscriptionPlan $plan) : bool
    {
        $activePlans = $user->subscriptions()->where('is_active', true)->where('subscription_plan_id', $plan->id)->get();
        return (bool) $activePlans;
    }

    public static function getMaxCollages(User $user) : int
    {
        return 3;
    }

    public static function canCreateCollage(User $user) : bool
    {
        return count($user->collages) < self::getMaxCollages($user);
    }
}
