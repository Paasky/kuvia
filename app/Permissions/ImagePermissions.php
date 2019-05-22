<?php

namespace App\Permissions;

use App\Models\Image;
use App\User;

class ImagePermissions extends CommonPermissions
{
    public static function list(User $user = null) : void
    {
        self::verifyIsAdmin($user);
    }

    public static function view(Image $image, User $user = null) : void
    {
        self::verifyCanModerate($image->collage, $user);
    }

    public static function delete(Image $image, User $user = null) : void
    {
        if($image->user_id === ($user->id ?? null)) {
            return;
        }
        if($image->collage->user_id === ($user->id ?? null)) {
            return;
        }
        self::verifyIsAdmin($user);
    }
/*
    public static function moderateImage(Image $image) : void
    {
        self::verifyCanModerateCollage($image->collage);
    }
*/
}
