<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollageCreateRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Models\Collage;
use App\Models\Image;
use App\Permissions\CollagePermissions;
use App\Repositories\ImageRepo;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Spatie\Image\Manipulations;

class CollageController extends Controller
{
    public function create(CollageCreateRequest $request) : void
    {
        $user = Auth::user();
        CollagePermissions::create($user);

        do {
            $key =
                str_shuffle('bcdfghjklmnpqrstvwxz') .
                str_shuffle('aeiouy') .
                str_shuffle('bcdfghjklmnpqrstvwxz') .
                str_shuffle('aeiouy') .
                str_shuffle('bcdfghjklmnpqrstvwxz');
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

        redirect('/collages');
    }

    public function list() : array
    {
        CollagePermissions::list(Auth::user());
        return Collage::all();
    }
/*
    public function listPublic() : array
    {
        return Collage::where('visibility', 'public')
            ->where('disabled_at', null)
            ->get(['title', 'id', 'created_at']);
    }
*/
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

    public function edit(Request $request, string $id) : Collage
    {
        $collage = Collage::findOrFail($id);
        CollagePermissions::edit($collage, $this->user);
        $collage->update($request->all());
        return $collage;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(string $id) : bool
    {
        $collage = Collage::findOrFail($id);
        CollagePermissions::delete($collage, $this->user);
        $collage->delete();
        return true;
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
/*
        if(Image::where('orig_filename', $file->getFilename())->where('created_at', '>', Carbon::parse('-24h'))) {
            throw new \InvalidArgumentException(__('This image was uploaded already, try another one'));
        }
*/
        $filePath = $file->getRealPath();
        $imageData = \Spatie\Image\Image::load($filePath);

        if($imageData->getWidth() > $imageData->getHeight()) {
            $type = Image::LANDSCAPE;
        } else {
            $type = Image::PORTRAIT;
        }

        if($imageData->getWidth() > 4096 || $imageData->getHeight() > 4096) {
            $imageData
                ->fit(Manipulations::FIT_CONTAIN, 4096, 4096)
                ->save($filePath);
        }

        $image = Image::create([
            'collage_id' => $collage->id,
            'user_id' => Auth::user()->id ?? null,
            'type' => $type,
        ]);

        $image
            ->addMedia($file->getRealPath())
            ->toMediaCollection($type);

        $request->session()->flash('status', __("Your image has been uploaded and will be shown in the collection shortly!"));
        return redirect("/u/{$collageKey}");
    }

    public function imagesForUi(Collage $collage, int $afterImageId = null) : Collection
    {
        CollagePermissions::viewImages($collage, Auth::user());
        $imageRepo = (new ImageRepo())->inCollage($collage->id);
        if($afterImageId) {
            $imageRepo->uploadedAfter($afterImageId);
        }
        return $imageRepo->get(['id', 'type', 'link']);
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
