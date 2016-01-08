<?php

use app\models\Event;
use app\models\Pass;
use app\widgets\DateRangePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Pass */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pass-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency')->dropDownList(Pass::$currencies) ?>

    <?= $form->field($model, 'full')->checkbox(); ?>

    <?= DateRangePicker::widget([
        'form' => $form,
        'model' => $model,
        'fromAttr' => 'available_from',
        'toAttr' => 'available_to',
    ]) ?>

    <?= $form->field($model, 'event_id')->dropDownList(ArrayHelper::map(Event::find()->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
