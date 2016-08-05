<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = Yii::t('app', 'Create Event');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => true,
    ]); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_fields', [
        'model' => $model,
        'form' => $form,
    ]) ?>

    <?= Html::submitButton(Yii::t('app', "Create"), [
        'class' => 'btn btn-success',
    ]) ?>

    <?php ActiveForm::end() ?>

</div>
