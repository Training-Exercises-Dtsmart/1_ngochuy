<?php

namespace app\models;
use yii\web\IdentityInterface;
use app\models\base\User as BaseUser;

class User extends BaseUser 
{
     const STATUS_ACTIVE = 1;
//    public static function findIdentity($id)
//    {
//        return static::findOne($id);
//    }
}
