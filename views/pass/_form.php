<?php

use app\models\Event;
use app\models\TemporaryPrice;
use app\widgets\DateRangePicker;
use app\widgets\PriceInput;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Pass */
/* @var $prices app\models\TemporaryPrice[] */
/* @var $widget app\widgets\ListForm In case this view is rendered inside a ListForm */
?>

<div class="pass-form">

    <?php if (isset($widget)):?>
        <li>
            <?= Html::encode(Html::getAttributeValue($model, 'description')).' - '.Html::a(Yii::t('app', "Close"), $widget->getCloseUrl()) ?>
        </li>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
    ]); ?>

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
    
    <?php ActiveForm::end() ?>

</div>
