<?php

use app\models\Artist;
use app\widgets\RelationList;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArtistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Artists');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artist-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Artist'), ['create'], ['class' => 'btn btn-success']) ?>
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
            'name',
            'real_name',
            'real_surname',
            'website',
            [
                'attribute' => 'danceIds',
                'value' => function (Artist $model) {
                    return RelationList::widget([
                        'model' => $model,
                        'relation' => 'dances',
                    ]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'groupIds',
                'value' => function (Artist $model) {
                    return RelationList::widget([
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
