<?php

namespace app\modules\users\forms;

use app\models\User;
use app\modules\users\resources\UserResource;


/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class SignInForm extends \app\models\form\LoginForm
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
