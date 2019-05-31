<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollageCreateRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Models\Collage;
use App\Models\Image;
use App\Permissions\CollagePermissions;
use App\Repositories\ImageRepo;
use chillerlan\QRCode\QRCode;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Spatie\Image\Manipulations;

class CollageController extends Controller
{
    public function create(CollageCreateRequest $request)
    {
        $user = Auth::user();
        CollagePermissions::create($user);

        do {
            $key =
                substr(str_shuffle('bcdfghjklmnpqrstvwxz'), 0, 1) .
                substr(str_shuffle('aeiouy'), 0, 1) .
                substr(str_shuffle('bcdfghjklmnpqrstvwxz'), 0, 1) .
                substr(str_shuffle('aeiouy'), 0, 1) .
                substr(str_shuffle('bcdfghjklmnpqrstvwxz'), 0, 1);
        } while (
            Collage::where('key', $key)->exists()
        );
        $params = [
            'user_id' => $user->id,
            'title' => $request->get('title'),
            'publicity' => Collage::PUBLICITY_LINK_ONLY,
            'key' => $key,
        ];
        Collage::create($params);

        session()->flash('success', __('Collage created'));
        return redirect()->to('/collages');
    }

    public function list() : array
    {
        CollagePermissions::list(Auth::user());
        return Collage::all();
    }

    public function show(int $id)
    {
        $collage = Collage::findOrFail($id);
        CollagePermissions::view($collage, Auth::user());
        $qr = new QRCode();
        return View::make(
            'collage',
            [
                'collage' => $collage,
                'qr' => $qr->render($collage->short_url),
            ]
        );
    }

    public function update(Request $request, Collage $collage) : Collage
    {
        CollagePermissions::edit($collage, Auth::user());
        $collage->update($request->all());
        session()->flash('success', __('Collage updated'));
        return redirect()->back();
    }

    public function delete(Collage $collage)
    {
        CollagePermissions::delete($collage, Auth::user());
        $collage->delete();
        session()->flash('success', __('Collage deleted'));
        return redirect()->back();
    }

    public function showUploadImage(string $collageKey)
    {
        return View
            ::make('image-upload')
            ->with(
                'collage',
                Collage
                    ::where('key', $collageKey)
                    ->firstOrFail()
            );
    }

    public function uploadImage(ImageUploadRequest $request, string $collageKey)
    {
        /** @var Collage $collage */
        $collage = Collage::where('key', $collageKey)->firstOrFail();
        CollagePermissions::uploadImage($collage, Auth::user());

        $file = $request->file('image');
        $imageData = \Spatie\Image\Image::load($file->getRealPath());
        $imageData->orientation('auto');
        if($imageData->getWidth() > 4096 || $imageData->getHeight() > 4096) {
            $imageData->fit(Manipulations::FIT_CONTAIN, 4096, 4096);
        }
        $imageData->save();

        $type = $imageData->getWidth() > $imageData->getHeight() ? Image::LANDSCAPE : Image::PORTRAIT;

        $image = Image::create([
            'collage_id' => $collage->id,
            'user_id' => Auth::user()->id ?? null,
            'type' => $type,
            'orig_filename' => $file->getFilename(),
        ]);

        $image
            ->addMedia($file->getRealPath())
            ->toMediaCollection($type);

        session()->flash('success', __("Your image has been uploaded and will be shown in the collection shortly!"));
        return redirect("/u/{$collageKey}");
    }

    public function images(Collage $collage)
    {
        return View
            ::make('collage-images')
            ->with('collage', $collage);
    }

    public function imagesForUi(Collage $collage, int $afterImageId = null) : Collection
    {
        CollagePermissions::viewImages($collage, Auth::user());
        $imageRepo = (new ImageRepo())->inCollage($collage->id);
        if($afterImageId) {
            $imageRepo->uploadedAfter($afterImageId);
        }
        return collect($imageRepo->get()->map(function (Image $image){
            return $image->only(['id', 'type', 'link']);
        }));
    }
/*
    public function moderationQueue(int $id) : array
    {
        $collage = Collage::findOrFail($id);
        CollagePermissions::viewCollageModeration($collage);

        return $collage->images()->where('approved_at', null)->get();
    }

    public function createModerator(Request $request, int $id) : CollageModerator
    {
        $collage = Collage::findOrFail($id);
        $moderator = User::findOrFail($request->get('user_id'));
        CollagePermissions::createCollageModerator($collage, $moderator);
        return CollageModerator::create([
            'collage_id' => $collage->id,
            'user_id' => $moderator->id,
        ]);
    }

    public function moderators(int $id) : array
    {
        $collage = Collage::findOrFail($id);
        CollagePermissions::viewCollageModeration($collage);
        return $collage->moderators()->get();
    }

    public function purchases(int $id) : array
    {
        $collage = Collage::findOrFail($id);
        CollagePermissions::viewCollage($collage);
        return $collage->purchases()->get();
    }
*/
}
