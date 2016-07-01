<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p><?= Html::encode(Yii::t('app', 'Hello {username},', ['username' => $user->username])) ?></p>

    <p><?= Yii::t('app', 'Follow the link below to reset your password:') ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
