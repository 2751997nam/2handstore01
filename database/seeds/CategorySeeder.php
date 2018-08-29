<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = [
            'Fashions',
            'Vehicles',
            'Electronics',
            'Furnitures',
            'Books',
            'Toys',
            'Jewellery',
            'Other'
        ];
        $i = 1;
        foreach ($category as $value) {
            Category::create([
                'name' => $value,
                'slug' => str_slug($value),
                'thumbnail' => config('site.thumbnail') . 'cate' . $i . '.jpeg',
                'parent_id' => null
            ]);
        }
    }
}
