<?php

use app\models\Link;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */
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

    <div class="panel-body">
        <label>Links</label>

        <?php Pjax::begin(); ?>
        <?php $form = ActiveForm::begin([
                'action' => Url::to(['create-link']),
                'options' => [
                    'data-pjax' => true,
                ],
            ]); ?>

        <?= Html::activeHiddenInput($model->newLink, 'event_id') ?>
        <?= $form->field($model->newLink, 'title')->textInput() ?>
        <?= $form->field($model->newLink, 'url')->textInput() ?>
        <?= Html::submitButton("Add", ['class' => 'btn btn-danger']) ?>

        <?php ActiveForm::end() ?>
        <?php Pjax::end(); ?>

        <?php Pjax::begin(); ?>
        <?= GridView::widget([
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
                ],
            ],
        ]) ?>
        <?php Pjax::end(); ?>

    </div>

</div>

<?php