<?php

namespace app\modules\models\form;

use app\models\User;
use app\modules\models\HttpStatus;


class UserForm extends User
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['name', 'password'], 'required'],
            ]
        );
    }

}
