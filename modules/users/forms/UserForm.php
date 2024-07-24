<?php

namespace app\modules\users\forms;

use app\models\User;


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
