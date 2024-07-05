<?php

namespace app\modules\models;

use app\models\Product as BaseProduct;

class Product extends BaseProduct
{

    public function fields()
    {
//        $fields = \Yii::$app->request->get("field_only");
//        return explode(",", $fields);
//        return [
//            "name",
//            "id",
//            "categoryName"
//        ];
        return array_merge(parent::fields(), [
            "category_name" => "categoryName",
            "name" => function () {
                return strtolower($this->name);
            }
        ]);
    }

    public function getCategoryName()
    {
        return $this->categoryProduct->name;
    }
}