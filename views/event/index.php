<?php

use app\models\Event;
use app\widgets\RelationLinks;
use yii\helpers\ArrayHelper;
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
            [
                'attribute' => 'imageUrl',
                'format' => 'image',
            ],
            'id',
            [
                'attribute' => 'name',
                'value' => function (Event $model) {
                    $url = (strpos($model->website, '://')) === false ? "http://$model->website" : $model->website;
                    return $model->website ? Html::a($model->name, $url, ['target'=>'_blank']) : $model->name;
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'danceIds',
                'value' => function (Event $model) {
                    return Html::ul(ArrayHelper::getColumn($model->getDances()->orderBy('name')->all(), 'name'));
                },
                'format' => 'html',
            ],
            'start_date:date',
            'end_date:date',
            'address',
            [
                'attribute' => 'groupIds',
                'value' => function (Event $model) {
                    return RelationLinks::widget([
                        'model' => $model,
                        'relation' => 'groups',
                    ]);
                },
                'format' => 'html',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
