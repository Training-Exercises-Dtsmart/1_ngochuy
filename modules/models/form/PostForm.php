<?php

namespace app\modules\models\form;

use app\models\Post;


class PostForm extends Post
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['title', 'content', 'short_description', 'slug'], 'required'],

            ]
        );
    }
}