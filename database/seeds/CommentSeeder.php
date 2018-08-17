<?php

use App\User;
use App\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(\App\User::class, 10)->create()->each(function ($user) {
        //     factory(\App\Product::class, 3)->create([
        //         'user_id' => $user->id,
        //     ])->each(function ($product) {
        //         factory(\App\User::class, 5)->create()->each(function ($commentUser) use ($product) {
        //             factory(\App\Comment::class)->create([
        //                 'user_id' => $commentUser->id,
        //                 'product_id' => $product->id,
        //             ]);
        //         });
        //     });
        // });
        $faker = Faker\Factory::create();
        $users = User::all();

        foreach ($users as $user) {
            $temps = rand(1, 10);
            foreach (range(1, $temps) as $temp) {
                Comment::create([
                    'user_id' => $user->id,
                    'product_id' => rand(1, 1000),
                    'content' => $faker->sentence(),
                    'parent_id' => null
                ]);
            }
        }
    }
}
