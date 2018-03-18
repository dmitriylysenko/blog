<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Comment
 *
 * @property-read \App\User $author
 * @property-read \App\Post $post
 * @mixin \Eloquent
 * @property int $id
 * @property string $text
 * @property int $user_id
 * @property int $post_id
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUserId($value)
 */
class Comment extends Model
{
    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    public function allow()
    {
        $this->status = 1;
        $this->save();
    }

    public function disallow()
    {
        $this->status = 0;
        $this->save();
    }

    public function toggleStatus()
    {
        if ($this->status == 0) {
            return $this->allow();
        }
        return $this->disallow();
    }

    /**
     * @throws \Exception
     */
    public function remove()
    {
        $this->delete();
    }
}
