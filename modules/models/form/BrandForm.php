<?php

namespace app\modules\models\form;

use app\modules\models\Brand;


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
}
