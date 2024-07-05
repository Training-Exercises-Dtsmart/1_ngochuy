<?php

namespace app\modules\product;

/**
 * product module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\product\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->defaultRoute = 'product';
        parent::init();

        // custom initialization code goes here
    }
}
