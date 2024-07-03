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
    public $quantity;
    public $is_best_sell;
    public $product_status;
    public $user_id;

    public function rules()
    {
        return [
            [['name', 'price', 'category_product_id', 'slug', 'quantity', 'user_id'], 'required'],
            [['description', 'details'], 'string'],
            [['price'], 'number'],
            [['category_product_id', 'quantity', 'user_id'], 'integer'],
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
