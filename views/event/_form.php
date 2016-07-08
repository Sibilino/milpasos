<?php

use app\models\Dance;
use app\models\Group;
use app\widgets\DateRangePicker;
use app\widgets\GeoSearch;
use app\widgets\GridForm;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $newLink app\models\Link */
/* @var $newPass app\models\Pass */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data',
    ]]); ?>

    <div class="row">
        <div class="col-sm-6">
            <?php if ($model->imageUrl): ?>
                <img src="<?= $model->imageUrl ?>">
            <?php endif; ?>
            <?= $form->field($model, 'imageUrl')->fileInput() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'website')->textInput(['maxlength' => true])?>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'danceIds')->checkboxList(ArrayHelper::map(Dance::find()->all(), 'id', 'name')) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'groupIds')->checkboxList(ArrayHelper::map(Group::find()->all(), 'id', 'name')) ?>
        </div>
    </div>



    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'address')->widget(GeoSearch::className(), [
                'mapOptions' => [
                    'style' => [
                        'width' => '200px',
                        'height' => '200px',
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= DateRangePicker::widget([
                'form' => $form,
                'model' => $model,
                'fromAttr' => 'start_date',
                'toAttr' => 'end_date',
                'pickerConfig' => [
                    'options' => [
                        'class' => 'form-control',
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <?php if (!$model->isNewRecord && isset($newLink)):?>
        <div>
            <div id="links">
                <h2><?= Yii::t('app', "Other links") ?></h2>

                <?php $form = GridForm::begin([
                    'gridOptions' => [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getLinks(),
                        ]),
                        "columns" => [
                            [
                                'class' => SerialColumn::className(),
                            ],
                            'title',
                            [
                                'attribute' => 'url',
                                "format" => 'url',
                            ],
                            [
                                'class' => ActionColumn::className(),
                                'header' => 'actions',
                                'template' => '{update}{delete}',
                                'controller' => 'link',
                            ],
                        ],
                    ],
                ]); ?>

                <?= Html::activeHiddenInput($newLink, 'event_id') ?>
                <?= $form->field($newLink, 'title')->textInput() ?>
                <?= $form->field($newLink, 'url')->textInput() ?>
                <?= Html::submitButton("Add", ['class' => 'btn btn-danger']) ?>

                <?php GridForm::end() ?>

            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
