<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Filesystem\Filesystem;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * App\Models\Image
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property int $collage_id
 * @property string $ext_url
 * @property string $link
 * @property string $orig_filename
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read \App\Models\Collage $collage
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read \App\User $uploader
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereCollageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereUserId($value)
 * @mixin \Eloquent
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereType($value)
 */
class Image extends KuviaModel implements HasMedia
{
    use HasMediaTrait;

    const LANDSCAPE = 'landscape';
    const PORTRAIT = 'portrait';
    const CONVERSION_REGULAR_LANDSCAPE = 'regularLandscape';
    const CONVERSION_REGULAR_PORTRAIT = 'regularPortrait';

    protected $fillable = [
        'user_id',
        'collage_id',
        'ext_url',
        'type',
    ];

    protected $appends = [
        'link',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function collage(): BelongsTo
    {
        return $this->belongsTo(Collage::class);
    }

    public function getConversion() : string
    {
        switch ($this->type) {
            case self::LANDSCAPE:
                return self::CONVERSION_REGULAR_LANDSCAPE;
            case self::PORTRAIT:
                return self::CONVERSION_REGULAR_PORTRAIT;
            default:
                throw new \BadFunctionCallException("Image $this->id has an invalid type $this->type");
        }
    }

    public function getLinkAttribute() : string
    {
        if($this->ext_url) {
            return $this->ext_url;
        }

        return $this->getFirstMediaUrl($this->type);
    }

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection(self::LANDSCAPE)
            ->singleFile();

        $this
            ->addMediaCollection(self::PORTRAIT)
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this
            ->addMediaConversion(self::CONVERSION_REGULAR_LANDSCAPE)
            ->width(1024)
            ->height(768)
            ->sharpen(10)
            ->performOnCollections([self::LANDSCAPE]);

        $this
            ->addMediaConversion(self::PORTRAIT)
            ->width(768)
            ->height(1024)
            ->sharpen(10)
            ->performOnCollections([self::CONVERSION_REGULAR_PORTRAIT]);
    }

    public function delete()
    {
        $fs = new Filesystem();
        foreach($this->media as $media) {
            $fs->delete($media->getPath());
            $media->delete();
        }
        return parent::delete();
    }
}
