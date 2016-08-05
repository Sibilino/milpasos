<?php

use app\models\Dance;
use app\models\Group;
use app\widgets\DateRangePicker;
use app\widgets\GeoSearch;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $newLink app\models\Link */
?>

<div class="event-form">

    <div class="row">
        <div class="col-sm-6">
            <?php if ($model->imageUrl): ?>
                <img src="<?= $model->imageUrl ?>">
            <?php endif; ?>
            <?= $form->field($model, 'imageUrl')->fileInput() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'website')->textInput(['maxlength' => true])?>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'danceIds')->checkboxList(ArrayHelper::map(Dance::find()->all(), 'id', 'name')) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'groupIds')->checkboxList(ArrayHelper::map(Group::find()->all(), 'id', 'name')) ?>
        </div>
    </div>



    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'address')->widget(GeoSearch::className(), [
                'mapOptions' => [
                    'style' => [
                        'width' => '200px',
                        'height' => '200px',
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= DateRangePicker::widget([
                'form' => $form,
                'model' => $model,
                'fromAttr' => 'start_date',
                'toAttr' => 'end_date',
                'pickerConfig' => [
                    'options' => [
                        'class' => 'form-control',
                    ],
                ],
            ]) ?>
        </div>
    </div>
    
    <?php if (!$model->isNewRecord && isset($newLink)):?>
        <div id="links">
            <h2><?= Yii::t('app', "Other links") ?></h2>

            <?= GridView::widget([
                'emptyText' => Yii::t('app', "No other links for this event."),
                'dataProvider' => new ActiveDataProvider([
                    'query' => $model->getLinks(),
                ]),
                "columns" => [
                    'title',
                    [
                        'attribute' => 'url',
                        "format" => 'url',
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'header' => 'actions',
                        'template' => '{update}{delete}',
                        'controller' => 'link',
                    ],
                ],
            ]); ?>

            <div class="row">
                <div class="col-md-2">
                    <?= Yii::t('app', "Add a new Link:") ?>
                </div>
                <div class="col-md-4">
                    <?= Html::activeHiddenInput($newLink, 'event_id') ?>
                    <?= $form->field($newLink, 'title')->label(false)->textInput([
                        'placeholder' => Html::encode($newLink->getAttributeLabel('title')),
                        'class' => 'form-control',
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($newLink, 'url')->label(false)->textInput([
                        'placeholder' => Html::encode($newLink->getAttributeLabel('url')),
                        'class' => 'form-control',
                    ]) ?>
                </div>
            </div>

        </div>
    <?php endif; ?>

</div>

<?php
