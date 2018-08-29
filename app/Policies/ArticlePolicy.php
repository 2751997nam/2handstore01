<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Product;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function status(User $user, Product $product)
    {
        return $user->id == $product->user_id;
    }

    public function update(User $user, Product $product)
    {
        return $user->id == $product->user_id;
    }
}
