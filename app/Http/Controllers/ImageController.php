<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Permissions\ImagePermissions;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    public function delete(Image $image)
    {
        ImagePermissions::delete($image, Auth::user());
        $image->delete();
        session()->flash('success', __('Image deleted'));
        return redirect()->back();
    }

    public function get(Image $image)
    {
        ImagePermissions::view($image, Auth::user());
        return response()->file($image->getPath());
    }
}
