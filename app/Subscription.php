<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Subscription
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $email
 * @property string|null $token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereUpdatedAt($value)
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
