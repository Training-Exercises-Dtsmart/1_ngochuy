<?php

namespace app\models\form;

use yii\base\Model;
use app\models\Product;

class ProductForm extends Model
{
    public $name;
    public $description;
    public $price;
    public $category_product_id;
    public $slug;
    public $details;
    public $availabel_stock;
    public $is_best_sell;
    public $product_status;
    public $user_id;

    public function rules()
    {
        return [
            [['name', 'price', 'slug', 'availabel_stock',], 'required'],
            [['description', 'details'], 'string'],
            [['price'], 'number'],
            [['category_product_id', 'availabel_stock', 'user_id'], 'integer'],
            [['is_best_sell', 'product_status'], 'boolean'],
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $product = new Product();
            $product->attributes = $this->attributes;
            return $product->save();
        }
        return false;
    }
}
