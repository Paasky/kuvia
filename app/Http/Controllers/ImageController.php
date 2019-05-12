<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Permissions\ImagePermissions;

class ImageController extends Controller
{
    public function list() : array
    {
        ImagePermissions::list($this->user);
        return Image::all();
    }

    public function get(string $id) : Image
    {
        $image = Image::findOrFail($id);
        ImagePermissions::view($image, $this->user);
        return $image;
    }
/*
    public function edit(Request $request, string $id) : Image
    {
        $image = Image::findOrFail($id);
        ImagePermissions::editImage($this->user, $image);
        $image->update($request->all());
        return $image;
    }

    public function approve(string $id) : Image
    {
        $image = Image::findOrFail($id);
        ImagePermissions::moderateImage($this->user, $image);
        $moderator = CollageModeratorManager::getModerator(System::getLoggedInUser(), $image->getCollage());
        $image->update(['approved_at' => Carbon::now(), 'rejected_at' => null, 'processed_by_id' => $moderator->id]);
        return $image;
    }

    public function reject(int $id) : Image
    {
        $image = Image::findOrFail($id);
        ImagePermissions::moderateImage($image);
        $moderator = CollageModeratorManager::getModerator(System::getLoggedInUser(), $image->getCollage());
        $image->update(['rejected_at' => Carbon::now(), 'approved_at' => null, 'processed_by_id' => $moderator->id]);
        return $image;
    }

    public function moderator(int $id) : CollageModerator
    {
        $image = Image::findOrFail($id);
        ImagePermissions::moderateImage($image);
        return $image->getProcessedBy();
    }

    public function uploader(int $id) : Uploader
    {
        $image = Image::findOrFail($id);
        ImagePermissions::moderateImage($image);
        return $image->getUploader();
    }

    public function banUploader(int $id) : bool
    {
        $image = Image::findOrFail($id);
        ImagePermissions::moderateImage($image);

        CollageBan::create([
            'collage_id' => $image->getCollage()->id,
            'moderator_id' => CollageModeratorManager::getModerator(System::getLoggedInUser(), $image->getCollage())->id,
            'uploader_id' => $image->getUploader()->id,
        ]);

        return true;
    }
*/
    public function delete(string $id) : bool
    {
        $image = Image::findOrFail($id);
        ImagePermissions::delete($image, $this->user);
        $image->delete();
        return true;
    }
}
