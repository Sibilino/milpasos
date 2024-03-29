<?php

use app\widgets\RelationLinks;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Artist */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Artists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artist-view">

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
                'value' => Html::ul(ArrayHelper::getColumn($model->getDances()->orderBy('name')->all(), 'name')),
                'format' => 'html',
            ],
            'real_name',
            'real_surname',
            'website',
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

</div>
