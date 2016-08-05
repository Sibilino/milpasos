<?php

use app\widgets\DateRangePicker;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $prices app\models\TemporaryPrice[] */
/* @var $form yii\bootstrap\ActiveForm The active form widget within which to render these fields */
?>

<?php foreach ($prices as $i => $price): ?>
    <div class="well<?php if ($price->isNewRecord) echo ' new-price'; ?>">
        <?= $form->field($price, "[$i]price")->textInput(['maxlength' => true]) ?>
        <?= DateRangePicker::widget([
            'form' => $form,
            'model' => $price,
            'fromAttr' => "[$i]available_from",
            'toAttr' => "[$i]available_to",
        ]) ?>
    </div>

<?php endforeach; ?>
