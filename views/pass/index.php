<?php

use app\models\Pass;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Passes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pass-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Pass'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'description',
            [
                'attribute' => 'price',
                'value' => function (Pass $model) {
                    return Yii::$app->formatter->asCurrency($model->price, $model->currency);
                } ,
            ],
            [
                'attribute' => 'full',
                'format' => 'boolean',
                'filter' => Html::activeDropDownList($searchModel, 'full', [
                    Yii::t('app', "No"),
                    Yii::t('app', "Yes"),
                ],[
                    'class' => 'form-control',
                    'prompt' => '',
                ]),
            ],
            [
                'format' => 'raw',
                'value' => function ($model, $key, $index) {
                    return Html::a(Html::encode($model->event->name), ['event/view', 'id' => $model->event->id]);
                },
                'label' => Yii::t('app', 'Event'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
