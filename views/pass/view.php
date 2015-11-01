<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pass */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Passes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pass-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'description',
            [
                'attribute' => 'price',
                'value' => Yii::$app->formatter->asCurrency($model->price, $model->currency),
            ],
            'available_from:date',
            'available_to:date',
            [
                'format' => 'raw',
                'value' => Html::a($model->event->name, ['event/view', 'id' => $model->event->id]),
                'label' => Yii::t('app', 'Event'),
            ],
        ],
    ]) ?>

</div>
