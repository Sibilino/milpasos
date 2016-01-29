<?php

use app\models\Dance;
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
            'id',
            'name',
            'real_name',
            'real_surname',
            [
                'label' => $model->getAttributeLabel('danceIds'),
                'value' => implode(', ', array_map(function (Dance $d) { return ucfirst($d->name); }, $model->dances)),
            ],
            'website',
        ],
    ]) ?>

</div>
