<?php

namespace App\Repositories;

class ImageRepository extends EloquentRepository
{
    public function model()
    {
        return \App\Image::class;
    }

    public function setProductId($images_id, $product_id)
    {
        return $this->model->whereIn('id', $images_id)->update([
            'product_id' => $product_id
        ]);
    }
}
