<?php

use app\models\Event;
use app\models\Pass;
use app\models\TemporaryPrice;
use app\widgets\DateRangePicker;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Pass */
/* @var $newTemporaryPrice app\models\TemporaryPrice */
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

    <?php $form = ActiveForm::end(); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?php if (!$model->isNewRecord && isset($newTemporaryPrice)): ?>
        <div id="temporary-prices">
            <h2><?= Yii::t('app', "Temporary Prices") ?></h2>

            <?= $form->field($newTemporaryPrice, 'price')->textInput(['maxlength' => true]) ?>
            <?= DateRangePicker::widget([
                'form' => $form,
                'model' => $newTemporaryPrice,
                'fromAttr' => 'available_from',
                'toAttr' => 'available_to',
            ]) ?>
            
            <?= Html::submitButton("Add", ['class' => 'btn btn-danger']) ?>

            <?= GridView::widget([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $model->getTemporaryPrices(),
                ]),
                "columns" => [
                    [
                        'class' => SerialColumn::className(),
                    ],
                    [
                        'attribute' => 'price',
                        'value' => function (TemporaryPrice $tempPrice, $key, $index, DataColumn $column) use ($model) {
                            return $column->grid->formatter->asCurrency($tempPrice->price, $model->currency);
                        },
                    ],
                    'available_from:date',
                    'available_to:date',
                    [
                        'class' => ActionColumn::className(),
                        'header' => 'actions',
                        'template' => '{update}{delete}',
                        'controller' => 'temporary-price',
                    ],
                ],
            ]) ?>
        </div>
    
    <?php endif; ?>

    <?php ActiveForm::end() ?>

</div>
