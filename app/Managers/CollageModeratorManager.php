<?php

namespace App\Managers;

use App\Collage;
use App\CollageModerator;
use App\Product;
use App\Traits\SavesDetailedLog;
use App\User;
use Carbon\Carbon;

class CollageModeratorManager
{
    use SavesDetailedLog;

    public static function getModerator(User $user, Collage $collage) : CollageModerator
    {
        return CollageModerator::where('user_id', $user->id)->where('collage_id', $collage->id)->first();
    }

    public static function hasActiveProduct(Collage $collage, Product $product) : bool
    {
        $activePurchases = $collage->purchases()->where('disabled_at', null)->where('product_id', $product->id)->get();
        return (bool) $activePurchases;
    }

    public static function isModeratedBy(Collage $collage, User $user) : bool
    {
        $moderators = $collage->moderators()->where('disabled_at', null)->where('user_id', $user->id)->get();
        return (bool) $moderators;
    }

    public static function getImagesForUi(Collage $collage, ?Carbon $since = null) : array
    {
        if($collage->disabled_at){
            return [];
        }
        $imagesQuery = $collage->images()->whereNotNull('approved_at');
        if($since){
            $imagesQuery->where('created_at', '>=', $since);
        }
        return $imagesQuery->get(['url', 'created_at']);
    }
}
