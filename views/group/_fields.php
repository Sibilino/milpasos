<?php

use app\models\Artist;
use app\widgets\MultiAutoComplete;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Group */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="group-form">

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'artistIds')->widget(MultiAutoComplete::className(), [
                'data' => ArrayHelper::map(Artist::find()->orderBy('name')->all(), 'id', 'name'),
                'autoCompleteConfig' => [
                    'options' => [
                        'placeholder' => Yii::t('app', "Add more..."),
                        'class' => 'form-control',
                    ],
                ],
            ]) ?>

        </div>
        <div class="col-md-6">
            <?php if ($model->imageUrl): ?>
                <img src="<?= $model->imageUrl ?>">
            <?php endif; ?>
            <?= $form->field($model, 'imageUrl')->fileInput() ?>

        </div>
    </div>



</div>
