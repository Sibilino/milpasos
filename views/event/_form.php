<?php

use app\models\Dance;
use app\models\Pass;
use app\widgets\DateRangePicker;
use app\widgets\GridForm;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */
/* @var $newLink app\models\Link */
/* @var $newPass app\models\Pass */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'danceIds')->checkboxList(ArrayHelper::map(Dance::find()->all(), 'id', 'name')) ?>

    <?= DateRangePicker::widget([
        'form' => $form,
        'model' => $model,
        'fromAttr' => 'start_date',
        'toAttr' => 'end_date',
    ]) ?>

    <?= $form->field($model, 'address')->widget(app\widgets\GeoComplete::className()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord):?>
        <?php if (isset($newLink)): ?>
            <div id="links">
                <h2>Links</h2>

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
                <h2>Passes</h2>

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
                            'available_from:date',
                            'available_to:date',
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
                <?= $form->field($newPass, 'price')->textInput(['maxlength' => true]) ?>
                <?= $form->field($newPass, 'currency')->dropDownList(Pass::$currencies) ?>
                <?= DateRangePicker::widget([
                    'form' => $form,
                    'model' => $newPass,
                    'fromAttr' => 'available_from',
                    'toAttr' => 'available_to',
                ]) ?>
                <?= Html::submitButton("Add", ['class' => 'btn btn-danger']) ?>

                <?php GridForm::end() ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
