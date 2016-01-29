<?php

use app\models\Event;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Event'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'name',
                'value' => function (Event $model) {
                    $url = (strpos($model->website, '://')) === false ? "http://$model->website" : $model->website;
                    return $model->website ? Html::a($model->name, $url, ['target'=>'_blank']) : $model->name;
                },
                'format' => 'html',
            ],
            'start_date:date',
            'end_date:date',
            'address',
            // 'lon',
            // 'lat',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
