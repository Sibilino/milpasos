<?php

use app\models\TemporaryPrice;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pass */
/* @var $prices app\models\TemporaryPrice[] */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Pass',
]) . ' ' . $model->description;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Passes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="pass-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
    ]); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'form' => $form,
    ]) ?>

    <?= $this->render('/temporary-price/_list', [
        'prices' => $prices,
        'form' => $form,
    ]) ?>

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

    <?php ActiveForm::end() ?>

</div>
