<?php

namespace App\Repositories;

class CityRepository extends EloquentRepository
{
    public function model()
    {
        return \App\City::class;
    }

    public function cities()
    {
        $this->makeModel();

        return $this->orderBy('name', 'asc')->pluck('name', 'id')->all();
    }
}
