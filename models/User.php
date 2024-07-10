<?php

namespace app\models;
use yii\web\IdentityInterface;
use app\models\base\User as BaseUser;

class User extends BaseUser 
{
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
}
