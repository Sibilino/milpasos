<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TemporaryPrice */

$this->title = Yii::t('app', 'Create Temporary Price');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Temporary Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="temporary-price-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
