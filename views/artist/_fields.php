<?php

use app\models\Dance;
use app\models\Group;
use app\widgets\MultiAutoComplete;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Artist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="artist-form">
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'real_surname')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
    <div class="row">
        <div class="col-md-4">
            <?php if ($model->imageUrl): ?>
                <img src="<?= $model->imageUrl ?>">
            <?php endif; ?>
            <?= $form->field($model, 'imageUrl')->fileInput() ?>

        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'danceIds')->widget(MultiAutoComplete::className(), [
                'data' => ArrayHelper::map(Dance::find()->orderBy('name')->all(), 'id', 'name'),
                'autoCompleteConfig' => [
                    'options' => [
                        'placeholder' => Yii::t('app', "Add more..."),
                        'class' => 'form-control',
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'groupIds')->widget(MultiAutoComplete::className(), [
                'data' => ArrayHelper::map(Group::find()->orderBy('name')->all(), 'id', 'name'),
                'autoCompleteConfig' => [
                    'options' => [
                        'placeholder' => Yii::t('app', "Add more..."),
                        'class' => 'form-control',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
