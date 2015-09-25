<?php

use app\models\Link;
use app\models\LinkSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?= $form->field($model, 'end_date')->widget(DatePicker::className(), [
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?= $form->field($model, 'address')->widget(app\widgets\GeoComplete::className()) ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <label>Links</label>

            <?php $this->beginBlock('link-grid-footer'); ?>
                <div id="new-link" class="form-inline">
                    <?php
                    $newLink = new Link();
                    $i = count($model->links) + 1;
                    echo $form->field($newLink, "[$i]title")->textInput();
                    echo $form->field($newLink, "[$i]url")->textInput();
                    ?>
                    <?= Html::button("Add", [
                        "onclick" => "addLink()",
                        "class" => "btn btn-sm btn-success form-control",
                    ]) ?>
                </div>
            <?php $this->endBlock(); ?>

            <?php
            $linkSearch = new LinkSearch();
            $linkSearch->event_id = $model->id;
            echo GridView::widget([
                "dataProvider" => $linkSearch->search([]),
                "columns" => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    [
                        'attribute' => 'url',
                        "format" => 'url',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'delete',
                        'template' => '{delete}',
                    ],
                ],
                "layout" => "{summary}\n{items}\n".$this->blocks['link-grid-footer']."\n{pager}",
            ]) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php