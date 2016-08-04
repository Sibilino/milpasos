<?php

use app\widgets\PriceInput;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pass */
/* @var $form yii\bootstrap\ActiveForm The active form widget within which to render these fields */
?>

<div class="pass-form">

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->widget(PriceInput::className()) ?>

    <?= $form->field($model, 'full')->checkbox(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

</div>
