<?php

namespace App\Http\Transformer;




use App\Http\Models\Api\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {

        return $user->toArray();
    }
}
