<?php

use app\models\Dance;
use app\models\Group;
use app\models\Pass;
use app\widgets\DateRangePicker;
use app\widgets\GeoSearch;
use app\widgets\GridForm;
use app\widgets\PriceInput;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
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
            'fieldOptions' => [
                'options' => [
                    'class' => 'col-sm-6',
                ],
            ],
        ]) ?>
    </div>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true])?>

    <?= $form->field($model, 'address')->widget(GeoSearch::className(), [
        'mapOptions' => [
            'style' => [
                'width' => '200px',
                'height' => '200px',
            ],
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord):?>
        <?php if (isset($newLink)): ?>
            <div id="links">
                <h2><?= Yii::t('app', "Links") ?></h2>

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
        <?php endif;?>
        <?php if (isset($newPass)): ?>
            <div id="passes">
                <h2><?= Yii::t('app', "Passes") ?></h2>

                <?php $form = GridForm::begin([
                    'gridOptions' => [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getPasses(),
                        ]),
                        "columns" => [
                            [
                                'class' => SerialColumn::className(),
                            ],
                            'description',
                            [
                                'attribute' => 'price',
                                'value' => function (Pass $pass, $key, $index, DataColumn $column) {
                                    return $column->grid->formatter->asCurrency($pass->price, $pass->currency);
                                },
                            ],
                            [
                                'class' => ActionColumn::className(),
                                'header' => 'actions',
                                'template' => '{update}{delete}',
                                'controller' => 'pass',
                            ],
                        ],
                    ],
                ]); ?>

                <?= Html::activeHiddenInput($newPass, 'event_id') ?>
                <?= $form->field($newPass, 'description')->textInput(['maxlength' => true]) ?>
                <?= $form->field($newPass, 'full')->checkbox() ?>
                <?= $form->field($newPass, 'price')->widget(PriceInput::className()) ?>

                <?= Html::submitButton("Add", ['class' => 'btn btn-danger']) ?>

                <?php GridForm::end() ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
