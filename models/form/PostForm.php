<?php

namespace app\models\form;

use app\models\Post;
use yii\base\Model;

class PostForm extends Model
{
    public $title;
    public $content;
    public $price;
    public $category_post_id;
    public $status;
    public $image;
    public $thumbnail;
    public $short_description;
    public $slug;
    public $user_id;

    public function rules()
    {
        return [
            [['title', 'content', 'price', 'category_post_id', 'short_description', 'slug'], 'required'],
            [['description', 'slug', 'content'], 'string'],
            [['price'], 'number'],
            [['category_post_id','status', 'user_id'], 'integer'],
            [['title', 'short_description', 'slug'], 'string', 'max' => 255],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $post = new Post();
            $post->attributes = $this->attributes;
            return $post->save();
        }
        return false;
    }
}
