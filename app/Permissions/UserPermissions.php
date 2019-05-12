<?php
/**
 * Created by PhpStorm.
 * User: pekko.tuomisto
 * Date: 23.9.2018
 * Time: 13.13
 */

namespace App\Permissions;


use App\Managers\FriendshipManager;
use App\System;
use App\User;
use Illuminate\Validation\UnauthorizedException;

class UserPermissions extends CommonPermissions
{
    public static function viewAllUsers() : void
    {
        self::verifyIsAdmin();
    }

    public static function register() : void
    {
        if(System::isLoggedIn()){
            throw new UnauthorizedException('You are already logged in.');
        }
    }

    public static function viewUser(User $user) : void
    {
        self::verifyIsAdminOrCurrentUser($user);
    }

    public static function editUser(User $user) : void
    {
        self::verifyIsAdminOrCurrentUser($user);
    }

    public static function deleteUser(User $user) : void
    {
        self::verifyIsAdminOrCurrentUser($user);

        if($user->purchases() || $user->subscriptions()){
            throw new UnauthorizedException('Can\'t delete user with purchase or subscription history.');
        }
    }
    public static function viewAllFriendships() : void
    {
        self::verifyIsAdmin();
    }

    public static function createFriendship(User $forUser) : void
    {
        self::verifyIsLoggedIn();
        if(FriendshipManager::doesFriendshipExist(System::getLoggedInUser(), $forUser)){
            throw new UnauthorizedException('Friendship already exists.');
        }
    }

    public static function viewFriendship(\App\Friendship $friendship) : void
    {
        self::verifyIsLoggedIn();
        try {
            // user who created the friendship can
            self::verifyIsUser($friendship->getAddedBy());
        } catch (UnauthorizedException $e){
            // user who was added to the friendship & admin can
            self::verifyIsAdminOrCurrentUser($friendship->getAddedFor());
        }
    }

    public static function editFriendship(\App\Friendship $friendship) : void
    {
        self::verifyIsLoggedIn();
        try {
            // user who created the friendship can
            self::verifyIsUser($friendship->getAddedBy());
        } catch (UnauthorizedException $e){
            // user who was added to the friendship & admin can
            self::verifyIsAdminOrCurrentUser($friendship->getAddedFor());
        }
    }

    public static function deleteFriendship(\App\Friendship $friendship) : void
    {
        self::verifyIsLoggedIn();
        try {
            // user who created the friendship can
            self::verifyIsUser($friendship->getAddedBy());
        } catch (UnauthorizedException $e){
            // user who was added to the friendship & admin can
            self::verifyIsAdminOrCurrentUser($friendship->getAddedFor());
        }
    }
}
