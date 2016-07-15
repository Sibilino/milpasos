<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pass */
/* @var $prices app\models\TemporaryPrice[] */

$this->title = Yii::t('app', 'Create Pass');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Passes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pass-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'prices' => $prices,
    ]) ?>

</div>
