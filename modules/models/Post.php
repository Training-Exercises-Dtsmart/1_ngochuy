<?php

namespace app\modules\models;

use app\models\Post as BasePost;

class Post extends BasePost
{

    public function fields()
    {
        return array_merge(parent::fields(), [
            "category_product_name" => "categoryProduct",
        ]);
    }

    public function getCategoryProduct()
    {
        return $this->category ? $this->category->name : '';
    }
}