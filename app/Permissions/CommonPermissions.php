<?php
/**
 * Created by PhpStorm.
 * User: pekko.tuomisto
 * Date: 23.9.2018
 * Time: 13.13
 */

namespace App\Permissions;


use App\Managers\CollageManager;
use App\Managers\UserManager;
use App\Models\Collage;
use App\System;
use App\User;
use Illuminate\Validation\UnauthorizedException;

abstract class CommonPermissions
{
    protected static function verifyIsLoggedIn() : void
    {
        $user = System::getLoggedInUserOrNull();
        if(!$user){
            throw new UnauthorizedException('Not logged in.');
        }
    }

    protected static function verifyIsAdmin(User $user = null) : void
    {
        if(!UserManager::isAdmin($user)){
            throw new UnauthorizedException('You don\'t have permission.');
        }
    }

    protected static function verifyIsUser(\App\User $user) : void
    {
        self::verifyIsLoggedIn();

        $loggedInUser = System::getLoggedInUser();
        if($loggedInUser->id !== $user->id){
            throw new UnauthorizedException('You don\'t have permission.');
        }
    }

    protected static function verifyIsAdminOrCurrentUser(\App\User $user) : void
    {
        self::verifyIsLoggedIn();

        $loggedInUser = System::getLoggedInUser();
        if($loggedInUser->id !== $user->id && !UserManager::isAdmin($loggedInUser)){
            throw new UnauthorizedException('You don\'t have permission.');
        }
    }

    protected static function verifyCanModerate(Collage $collage, User $user = null) : void
    {
        if(!$user) {
            throw new UnauthorizedException("Must be logged in to moderate");
        }

        // owner can
        if($collage->user->id === $user->id){
            return;
        }

        // moderators can
        if(CollageManager::isModeratedBy($collage, $user)){
            return;
        }

        // admins can
        self::verifyIsAdmin();
    }
}
