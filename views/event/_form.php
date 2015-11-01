<?php

use app\models\Pass;
use app\widgets\GridForm;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\jui\DatePicker;
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

    <?= $form->field($model, 'start_date')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?= $form->field($model, 'end_date')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
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
                                'header' => 'delete',
                                'template' => '{delete}',
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
                                "format" => 'currency',
                            ],
                            'available_from:date',
                            'available_to:date',
                            [
                                'class' => ActionColumn::className(),
                                'header' => 'delete',
                                'template' => '{delete}',
                                'controller' => 'link',
                            ],
                        ],
                    ],
                ]); ?>

                <?= Html::activeHiddenInput($newPass, 'event_id') ?>
                <?= $form->field($newPass, 'description')->textInput(['maxlength' => true]) ?>
                <?= $form->field($newPass, 'price')->textInput(['maxlength' => true]) ?>
                <?= $form->field($newPass, 'currency')->dropDownList(Pass::$currencies) ?>
                <?= $form->field($newPass, 'available_from')->widget(DatePicker::className(), [
                    'dateFormat' => 'yyyy-MM-dd',
                ]) ?>
                <?= $form->field($newPass, 'available_to')->widget(DatePicker::className(), [
                    'dateFormat' => 'yyyy-MM-dd',
                ]) ?>
                <?= Html::submitButton("Add", ['class' => 'btn btn-danger']) ?>

                <?php GridForm::end() ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php