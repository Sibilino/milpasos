<?php

use app\models\Event;
use app\models\TemporaryPrice;
use app\widgets\DateRangePicker;
use app\widgets\PriceInput;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Pass */
/* @var $prices app\models\TemporaryPrice[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pass-form">

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->widget(PriceInput::className()) ?>

    <?= $form->field($model, 'full')->checkbox(); ?>

    <?= $form->field($model, 'event_id')->dropDownList(ArrayHelper::map(Event::find()->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <div id="temporary-prices">
        <h2><?= Yii::t('app', "Temporary Prices") ?></h2>

        <div  class="panel panel-default">
            <div class="panel-body">
                <?php foreach ($prices as $i => $price): ?>
                    <div class="well">
                        <?= $form->field($price, "[$i]price")->textInput(['maxlength' => true]) ?>
                        <?= DateRangePicker::widget([
                            'form' => $form,
                            'model' => $price,
                            'fromAttr' => "[$i]available_from",
                            'toAttr' => "[$i]available_to",
                        ]) ?>
                        <?= Html::submitButton(Yii::t('app', "Save"), ['class' => 'btn btn-success']) ?>
                    </div>

                <?php endforeach; ?>
            </div>
            <div class="panel-footer">
                <?= Html::submitButton(Yii::t('app', "Add"), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

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

</div>
