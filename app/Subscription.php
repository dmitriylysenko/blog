<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Subscription
 *
 * @mixin \Eloquent
 */
class Subscription extends Model
{
    public function add($email)
    {
        $sub = new static();
        $sub->email = $email;
        $sub->token = str_random(100);
        $sub->save();

        return $sub;
    }

    public function remove()
    {
        $this->delete();
    }
}
