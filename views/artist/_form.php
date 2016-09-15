<?php

use app\models\Dance;
use app\models\Group;
use app\widgets\MultiAutoComplete;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Artist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="artist-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'real_surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'danceIds')->widget(MultiAutoComplete::className(), [
        'data' => ArrayHelper::map(Dance::find()->orderBy('name')->all(), 'id', 'name'),
        'autoCompleteConfig' => [
            'options' => [
                'placeholder' => Yii::t('app', "Add more..."),
                'class' => 'form-control',
            ],
        ],
    ]) ?>

    <?= $form->field($model, 'groupIds')->widget(MultiAutoComplete::className(), [
        'data' => ArrayHelper::map(Group::find()->orderBy('name')->all(), 'id', 'name'),
        'autoCompleteConfig' => [
            'options' => [
                'placeholder' => Yii::t('app', "Add more..."),
            ],
        ],
    ]) ?>

    <?php if ($model->imageUrl): ?>
        <img src="<?= $model->imageUrl ?>">
    <?php endif; ?>
    <?= $form->field($model, 'imageUrl')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
