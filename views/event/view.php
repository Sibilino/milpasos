<?php

use app\models\Dance;
use app\models\Pass;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
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

    <?php if ($model->imageUrl): ?>
        <img src="<?= $model->imageUrl ?>">
    <?php endif; ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'start_date',
            'end_date',
            'address',
            [
                'label' => $model->getAttributeLabel('danceIds'),
                'value' => implode(', ', array_map(function (Dance $d) { return ucfirst($d->name); }, $model->dances)),
            ],
            'lon',
            'lat',
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
