<?php

namespace App\Repositories;

class ProductRepository extends EloquentRepository
{
    public function model()
    {
        return \App\Product::class;
    }
}
