<?php

namespace app\modules\shops\forms;

use app\modules\shops\models\Brand;


class BrandForm extends Brand
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['name', 'status'], 'required'],

            ]
        );
    }

     public function saveBrand()
     {
          if ($this->validate()) {
               $product = new \app\models\Brand();
               $product->attributes = $this->attributes;
               return $product->save();
          }
          return false;
     }
}
