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
use App\Friendship;
use App\Helper;
use App\Repositories\UserRepository;
use App\SubscriptionPlan;
use App\System;
use App\Traits\EncryptsData;
use App\Traits\SavesDetailedLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;

class FriendshipManager
{
    use EncryptsData, SavesDetailedLog;

    public static function doesFriendshipExist(User $user1, User $user2) : bool
    {
        $friendships = Friendship::where('added_by_id', $user1->id)->where('added_for_id', $user2->id)->get();
        if($friendships){
            return true;
        }

        $friendships = Friendship::where('added_by_id', $user2->id)->where('added_for_id', $user1->id)->get();
        if($friendships){
            return true;
        }

        return false;
    }
}
