<?php

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

    <?php if (!$model->isNewRecord && isset($newLink)): ?>
        <div class="panel-body">
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
    <?php endif; ?>
</div>

<?php