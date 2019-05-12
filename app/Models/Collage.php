<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Collage
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $title
 * @property string $publicity
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage wherePublicity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $key
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage whereShortUrl($value)
 * @property-read mixed $short_url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Collage whereKey($value)
 */
class Collage extends KuviaModel
{
    const PUBLICITY_PUBLIC = 'public';
    const PUBLICITY_LINK_ONLY = 'link_only';
    const PUBLICITY_FRIENDS = 'friends';
    const PUBLICITY_MODERATORS = 'moderators';
    const PUBLICITY_PRIVATE = 'private';

    protected $fillable = [
        'user_id',
        'title',
        'publicity',
    ];

    protected $appends = [
        'short_url',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images() : HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function getShortUrlAttribute() : string
    {
        return $this->key ? "u/{$this->key}" : '';
    }
}
