<?php

use yii\bootstrap\ActiveForm;
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

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
    ]); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'prices' => $prices,
        'form' => $form,
    ]) ?>
    
    <?php ActiveForm::end() ?>

</div>
