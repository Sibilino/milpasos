<?php

use app\models\Pass;
use app\widgets\RelationLinks;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view">

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
            [
                'attribute' => 'imageUrl',
                'format' => 'image',
            ],
            'id',
            'name',
            [
                'attribute' => 'danceIds',
                'value' => Html::ul(ArrayHelper::getColumn($model->dances, 'name')),
                'format' => 'html',
            ],
            'start_date',
            'end_date',
            'address',
            [
                'attribute' => 'groupIds',
                'value' => RelationLinks::widget([
                    'model' => $model,
                    'relation' => 'groups',
                ]),
                'format' => 'html',
            ],

        ],
    ]) ?>

    <div id="links">
        <h2><?= Yii::t('app', "Links") ?></h2>
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
                        'header' => 'actions',
                        'template' => '{update}{delete}',
                        'controller' => 'link',
                    ],
                ]
            ]) ?>
    </div>

    <div id="passes">
        <h2><?= Yii::t('app', "Passes") ?></h2>
        <?= GridView::widget([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $model->getPasses(),
                ]),
                "columns" => [
                    [
                        'class' => SerialColumn::className(),
                    ],
                    'description',
                    [
                        'attribute' => 'price',
                        'value' => function (Pass $pass, $key, $index, DataColumn $column) {
                            return $column->grid->formatter->asCurrency($pass->price, $pass->currency);
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'header' => 'actions',
                        'template' => '{update}{delete}',
                        'controller' => 'pass',
                    ],
                ]
            ]) ?>
    </div>
</div>
