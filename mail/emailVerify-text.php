<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/verify-email', 'token' => $user->verification_token]);
?>
<div>
     <p>Hello <?= Html::encode($user->name) ?>,</p>
     <p>Follow the link below to verify your email:</p>
     <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
