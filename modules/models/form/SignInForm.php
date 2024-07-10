<?php

namespace app\modules\models\form;

use app\models\User;
use app\modules\models\resource\UserResource;


/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class SignInForm extends \app\models\LoginForm
{
    public $_user = false;
    /**
     * Finds user by [[username]]
     *
     * @return UserResource|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = UserResource::findByName($this->name);
        }

        return $this->_user;
    }
}
