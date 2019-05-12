<?php

namespace App\Permissions;

use App\Managers\CollageModeratorManager;
use App\Managers\FriendshipManager;
use App\Managers\UploaderManager;
use App\Managers\UserManager;
use App\Models\Collage;
use App\User;
use Illuminate\Validation\UnauthorizedException;

class CollagePermissions extends CommonPermissions
{
    public static function list() : void
    {
        self::verifyIsAdmin();
    }

    public static function create(User $user = null) : void
    {
        if(!$user) {
            throw new UnauthorizedException("Must be logged in");
        }
        if(!UserManager::canCreateCollage($user)){
            throw new UnauthorizedException('Max collages limit reached.');
        }
    }

    public static function view(Collage $collage, User $user) : void
    {
        if($collage->user_id == $user->id) {
            return;
        }
        self::verifyIsAdmin($user);
    }

    public static function viewImages(Collage $collage, User $user = null) : void
    {
        switch ($collage->publicity){
            case Collage::PUBLICITY_PUBLIC:
                return;
            case Collage::PUBLICITY_LINK_ONLY:
                return;
            /*
            case Collage::PUBLICITY_FRIENDS:
                if(FriendshipManager::doesFriendshipExist($collage->user, $user)){
                    return;
                }
                break;
            case Collage::PUBLICITY_MODERATORS:
                self::verifyCanModerate($collage, $user);
                break;
            case Collage::PUBLICITY_PRIVATE:
            default:
                self::verifyIsAdminOrCurrentUser($user);
                return;
            */
        }
        throw new UnauthorizedException('Collage is not available.');
    }

    public static function uploadImage(Collage $collage, User $user = null) : void
    {
        self::viewImages($collage, $user);
        /*
        if(UploaderManager::isBanned($uploader, $collage)){
            throw new UnauthorizedException('You are banned from this collage');
        }
        */
    }

    public static function viewCollageModeration(Collage $collage) : void
    {
        self::verifyCanModerate($collage);
    }

    public static function edit(Collage $collage) : void
    {
        self::verifyIsAdminOrCurrentUser($collage->getUser());
    }

    public static function delete(Collage $collage) : void
    {
        self::verifyIsAdminOrCurrentUser($collage->getUser());
    }

    public static function viewAllCollageModerators() : void
    {
        self::verifyIsAdmin();
    }

    public static function createCollageModerator(Collage $collage, User $moderator) : void
    {
        self::verifyIsAdminOrCurrentUser($collage->getUser());
        if(CollageModeratorManager::isModeratedBy($collage, $moderator)){
            throw new UnauthorizedException('collage is already moderated by this user');
        }
    }

    public static function viewCollageModerator(\App\CollageModerator $collageModerator) : void
    {
        self::verifyIsLoggedIn();

        // all moderators can see each other
        self::verifyCanModerate($collageModerator->getCollage());
    }

    public static function editCollageModerator(\App\CollageModerator $collageModerator) : void
    {
        self::verifyIsLoggedIn();
        try {
            // moderator can edit self
            self::verifyIsUser($collageModerator->getUser());
            return;
        } catch (UnauthorizedException $e){

            // admin & collage owner can edit moderators
            self::verifyIsAdminOrCurrentUser($collageModerator->getCollage()->getUser());
        }
    }

    public static function deleteCollageModerator(\App\CollageModerator $collageModerator) : void
    {
        self::verifyIsLoggedIn();
        try {
            // moderator can edit self
            self::verifyIsUser($collageModerator->getUser());
            return;
        } catch (UnauthorizedException $e){

            // admin & collage owner can edit moderators
            self::verifyIsAdminOrCurrentUser($collageModerator->getCollage()->getUser());
        }
    }
}
