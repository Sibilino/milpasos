<?php
/* @var $model app\models\Event */
use yii\helpers\Html;

/* @var $key int */
/* @var $index int */
/* @var $widget yii\widgets\ListView */
/* @var $this yii\web\View */
?>

<div class="row event">

    <div class="col-sm-4 list-img">
        <?php if ($model->imageUrl) echo Html::img($model->imageUrl) ?>
    </div>

    <div class="col-sm-8 list-info">
        <h3><?= Html::encode($model->name) ?></h3>
        <p>
            <?php if ($model->summary) echo Html::encode($model->summary).'<br>' ?>
            <b><?= $model->city ?>, <span class="country"><?= $model->country ?></span></b>
        </p>
        <div class="date">
            <?= Yii::$app->formatter->asDate($model->start_date, 'short') ?> - <?= Yii::$app->formatter->asDate($model->end_date, 'short') ?>
        </div>
        <?php foreach ($model->dances as $dance): ?>
            <span class="ico-<?= Html::encode(strtolower($dance->name)) ?>"><?= Html::encode(ucfirst($dance->name)) ?></span>
        <?php endforeach; ?>
        <div class="price">
            <?php
                $price = $model->bestAvailablePrice();
                if ($price) :
            ?>
                <small><?= Yii::t('app', "from") ?></small><?= Yii::$app->formatter->asCurrency($price->price, $price->currency) ?>
            <?php endif; ?>
        </div>

    </div>

</div>
