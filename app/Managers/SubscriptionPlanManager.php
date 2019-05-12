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
use App\Product;
use App\Repositories\UserRepository;
use App\SubscriptionPlan;
use App\System;
use App\Traits\EncryptsData;
use App\Traits\SavesDetailedLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;

class SubscriptionPlanManager
{
    use SavesDetailedLog;

    public static function isActive(SubscriptionPlan $subscriptionPlan) : bool
    {
        return $subscriptionPlan->disabled_at ? false : true;
    }
}
