<?php 

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pass */
/* @var $prices app\models\TemporaryPrice[] */
/* @var $form yii\bootstrap\ActiveForm */
?>

<h4><?= Html::encode(Html::getAttributeValue($model, 'description')) ?> (<?= Yii::$app->formatter->asCurrency($model->price, $model->currency) ?>)</h4>

<?= $this->render('/pass/_fields', [
    'model' => $model,
    'prices' => $prices,
    'form' => $form,
]) ?>

<?= Html::a(Yii::t('app', "Delete"), ['/pass/delete', 'id' => $model->id], [
    'class' => 'btn btn-danger btn-xs',
    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
    'data-method' => 'post',
    'data-pjax' => 0,
]) ?>
    

