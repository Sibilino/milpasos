<?php

use app\widgets\DateRangePicker;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $prices app\models\TemporaryPrice[] */
/* @var $form yii\bootstrap\ActiveForm The active form widget within which to render these fields */
?>

<div id="temporary-prices">
    <h2><?= Yii::t('app', "Temporary Prices") ?></h2>

    <div  class="panel panel-default">
        <div class="panel-body">
            <?php foreach ($prices as $i => $price): ?>
                <div class="well">
                    <?= $form->field($price, "[$i]price")->textInput(['maxlength' => true]) ?>
                    <?= DateRangePicker::widget([
                        'form' => $form,
                        'model' => $price,
                        'fromAttr' => "[$i]available_from",
                        'toAttr' => "[$i]available_to",
                    ]) ?>
                </div>

            <?php endforeach; ?>
        </div>
        <div class="panel-footer">
            <?= Html::submitButton(Yii::t('app', "Add"), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    
</div>
