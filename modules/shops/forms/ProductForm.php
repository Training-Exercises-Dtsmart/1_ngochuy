<?php

namespace app\modules\shops\form;

use app\models\Product;


class ProductForm extends Product
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['name', 'price', 'slug', 'availabel_stock',], 'required'],

            ]
        );
    }
}
