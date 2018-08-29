<?php

namespace App\Repositories;

class CategoryRepository extends EloquentRepository
{
    public function model()
    {
        return \App\Category::class;
    }

    public function arrayCategories()
    {
        $this->makeModel();

        return $this->model->where('parent_id', '=', null)
                ->pluck('name', 'id')
                ->toArray();
    }

    public function findBySlug($slug)
    {
        $this->makeModel();

        $category = $this->model->where('slug', $slug)->first();
        if ($category) {
            return $category;
        }

        return false;
    }
}
