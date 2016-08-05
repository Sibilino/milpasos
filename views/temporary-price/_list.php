<?php

use app\widgets\DateRangePicker;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $prices app\models\TemporaryPrice[] */
/* @var $form yii\bootstrap\ActiveForm The active form widget within which to render these fields */
?>

<?php foreach ($prices as $i => $price): ?>
    <div class="well">
        <?= $form->field($price, "[$i]price")->textInput(['maxlength' => true]) ?>
        <?= DateRangePicker::widget([
            'form' => $form,
            'model' => $price,
            'fromAttr' => "[$i]available_from",
            'toAttr' => "[$i]available_to",
        ]) ?>
        <?php if (!$price->isNewRecord): ?>
            <?= Html::a(Yii::t('app', "Delete"), ['/temporary-price/delete', 'id' => $price->id], [
                'class' => 'btn btn-danger btn-sm',
                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'data-method' => 'post',
                'data-pjax' => 0,
            ]) ?>
        <?php endif; ?>
    </div>

<?php endforeach; ?>
