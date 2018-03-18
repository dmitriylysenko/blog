<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

/**
 * App\Post
 *
 * @property mixed image
 * @property-read \App\User $author
 * @property-read \App\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post findSimilarSlugs($attribute, $config, $slug)
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property int|null $category_id
 * @property int|null $user_id
 * @property int $status
 * @property int $views
 * @property int $is_featured
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereViews($value)
 */
class Post extends Model
{
    use Sluggable;

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    protected $fillable = ['title', 'content'];

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * @param $fields
     * @return static
     */
    public static function add($fields)
    {
        $post = new static();
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();

        return $post;
    }

    /**
     * @param $fields
     */
    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    /**
     * @throws \Exception
     */
    public function remove()
    {
        Storage::delete('uploads/', $this->image);
        $this->delete();
    }

    /**
     * @param $image
     */
    public function uploadImage($image)
    {
        if ($image == null) {
            return;
        }
        Storage::delete('uploads/', $this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();

    }

    /**
     * @return string
     */
    public function getImage()
    {
        if ($this->image == null) {
            return '/img/no-image.png';
        }
        return '/uploads/' . $this->image;
    }

    /**
     * @param $id
     */
    public function setCategory($id)
    {
        if ($id == null) {
            return;
        }

        $this->category_id = $id;
        $this->save();
    }

    /**
     * @param $ids
     */
    public function setTags($ids)
    {
        if ($ids == null) {
            return;
        }
        $this->tags()->sync($ids);
    }

    public function setDraft()
    {
        $this->status = self::IS_DRAFT;
        $this->save();
    }

    public function setPublic()
    {
        $this->status = self::IS_PUBLIC;
        $this->save();
    }

    /**
     * @param $value
     */
    public function toggleStatus($value)
    {
        if ($value == null) {
            return $this->setDraft();
        }
        return $this->setPublic();
    }

    public function setFeatured()
    {
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart()
    {
        $this->is_featured = 0;
        $this->save();
    }

    /**
     * @param $value
     */
    public function toggleFeatured($value)
    {
        if ($value == null) {
            return $this->setStandart();
        }
        return $this->setFeatured();
    }
}
