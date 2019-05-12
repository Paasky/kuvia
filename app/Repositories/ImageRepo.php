<?php


namespace App\Repositories;


use App\Models\Image;

class ImageRepo extends Repository
{
    public function __construct()
    {
        parent::__construct(Image::class);
    }

    public function inCollage(int $collageId) : self
    {
        $this->query->where('collage_id', $collageId);
        return $this;
    }

    public function uploadedAfter(int $imageId) : self
    {
        $this->query->where('id', '>', $imageId);
        return $this;
    }
}
