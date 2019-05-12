<?php

namespace App\Managers;

use App\Collage;
use App\Product;
use App\System;
use App\Traits\SavesDetailedLog;
use App\Uploader;
use App\User;

class UploaderManager
{
    use SavesDetailedLog;

    public static function isBanned(Uploader $uploader, Collage $collage) : bool
    {
        $bans = $uploader->bans()->where('collage_id', $collage->id)->get();
        return (bool) $bans;
    }

    public static function getOrCreate() : Uploader
    {
        $uploader = null;

        $loggedInUser = System::getLoggedInUserOrNull();
        if($loggedInUser) {
            $uploader = Uploader::where('user_id', $loggedInUser->id)->first();
        } else {
            $uploaderIdentifier = \Request::cookie('kuvia.io.uploaderIdentifier');
            if($uploaderIdentifier) {
                $uploader = Uploader::where('identifier', $uploaderIdentifier)->first();
            }
            if(!$uploader) {
                $uploader = Uploader::where('ip', System::getClientIp())
                                    ->where('fingerprint', System::getClientFingerprint())
                                    ->first();
            }
        }

        if($uploader){
            return $uploader;
        }

        return self::create($loggedInUser);
    }

    protected static function create(?User $loggedInUser) : Uploader
    {
        $uploaderIdentifier = time() . '-' . str_random();
        \Cookie::queue(\Cookie::forever('kuvia.io.uploaderIdentifier', $uploaderIdentifier));

        $attributes = [
            'ip' => System::getClientIp(),
            'fingerprint' => System::getClientFingerprint(),
            'identifier' => $uploaderIdentifier,
        ];

        if($loggedInUser){
            $attributes['user_id'] = $loggedInUser->id;
        }

        return Uploader::create($attributes);
    }
}
