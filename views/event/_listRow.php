<?php
/* @var $model app\models\Event */
use yii\helpers\Html;

/* @var $key int */
/* @var $index int */
/* @var $widget yii\widgets\ListView */
/* @var $this yii\web\View */
?>

<div class="row event" > <!-- añadir aquí el evento de click --> 

    <div class="col-sm-3 col-xs-4 list-img">
     	<div class="img-price-block">
        	<?php if ($model->imageUrl) echo Html::img($model->imageUrl) ?>
            <?php
                $price = $model->bestAvailablePrice();
                if ($price) :
            ?>
                <small><?= Yii::t('app', "from") ?></small><?= Yii::$app->formatter->asCurrency($price->price, $price->currency) ?>
            <?php endif; ?>
   		</div>
    </div>


    <div class="col-sm-9 col-xs-8 list-info">
        <h3><?= Html::encode($model->name) ?></h3>
        <p>
            <?php if ($model->summary) echo Html::encode($model->summary).'<br>' ?>
            <b><?= $model->city ?>City, Country<span class="country"><?= $model->country ?></span></b>
        </p>
        <div class="date">
            <?= Yii::$app->formatter->asDate($model->start_date, 'short') ?> - <?= Yii::$app->formatter->asDate($model->end_date, 'short') ?>
        </div>
        <?php foreach ($model->dances as $dance): ?>
            <span class="ico-<?= Html::encode(strtolower($dance->name)) ?>" title="<?= Html::encode(strtolower($dance->name)) ?>"><?= Html::encode(ucfirst($dance->name)) ?></span>
        <?php endforeach; ?>
        <div class="more-info">
        More Info
    </div>
    </div>


</div>
