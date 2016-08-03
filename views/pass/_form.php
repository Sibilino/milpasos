<?php

use app\models\Event;
use app\widgets\PriceInput;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Pass */
/* @var $listWidget app\widgets\ListForm In case this view is rendered inside a ListForm */
/* @var $form yii\bootstrap\ActiveForm The active form widget within which to render these fields */
?>

<div class="pass-form">

    <?php if (isset($listWidget)):?>
        <li>
            <?= Html::encode(Html::getAttributeValue($model, 'description')).' - '.Html::a(Yii::t('app', "Close"), $listWidget->getCloseUrl()) ?>
        </li>
    <?php endif; ?>
    
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->widget(PriceInput::className()) ?>

    <?= $form->field($model, 'full')->checkbox(); ?>

    <?= $form->field($model, 'event_id')->dropDownList(ArrayHelper::map(Event::find()->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

</div>
