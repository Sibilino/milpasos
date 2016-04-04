<?php

use app\models\Dance;
use app\models\Group;
use app\models\Pass;
use app\widgets\DateRangePicker;
use app\widgets\GridForm;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\SerialColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */
/* @var $newLink app\models\Link */
/* @var $newPass app\models\Pass */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?php if ($model->imageUrl): ?>
        <img src="<?= $model->imageUrl ?>">
    <?php endif; ?>
    <?= $form->field($model, 'imageUrl')->fileInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'danceIds')->checkboxList(ArrayHelper::map(Dance::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'groupIds')->checkboxList(ArrayHelper::map(Group::find()->all(), 'id', 'name')) ?>

    <?= DateRangePicker::widget([
        'form' => $form,
        'model' => $model,
        'fromAttr' => 'start_date',
        'toAttr' => 'end_date',
    ]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true])?>

    <input type="text" id="pac" />
    <div id="map" style="width:300px;height:300px;"></div>

    <?php $this->registerJs("
        var map;
        function initMap() {
            var autocomplete = new google.maps.places.Autocomplete(document.getElementById('pac'));
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                map.setCenter(place.geometry.location);
                map.aa(10); // About city level
                alert('lon: '+place.geometry.location.lng()+', lat: '+place.geometry.location.lat());
            });
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 8
            });
        }
    ", View::POS_BEGIN) ?>

    <?php $this->registerJsFile("https://maps.googleapis.com/maps/api/js?key=AIzaSyBEr0tOImJExGdG9hriZazaa1zgZbLhu7Y&libraries=places&callback=initMap",
        [
            'async' => true,
            'defer' => true,
            'position' => View::POS_END,
        ]
    )?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord):?>
        <?php if (isset($newLink)): ?>
            <div id="links">
                <h2><?= Yii::t('app', "Links") ?></h2>

                <?php $form = GridForm::begin([
                    'gridOptions' => [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getLinks(),
                        ]),
                        "columns" => [
                            [
                                'class' => SerialColumn::className(),
                            ],
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
                    ],
                ]); ?>

                <?= Html::activeHiddenInput($newLink, 'event_id') ?>
                <?= $form->field($newLink, 'title')->textInput() ?>
                <?= $form->field($newLink, 'url')->textInput() ?>
                <?= Html::submitButton("Add", ['class' => 'btn btn-danger']) ?>

                <?php GridForm::end() ?>

            </div>
        <?php endif;?>
        <?php if (isset($newPass)): ?>
            <div id="passes">
                <h2><?= Yii::t('app', "Passes") ?></h2>

                <?php $form = GridForm::begin([
                    'gridOptions' => [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getPasses(),
                        ]),
                        "columns" => [
                            [
                                'class' => SerialColumn::className(),
                            ],
                            'description',
                            [
                                'attribute' => 'price',
                                'value' => function (Pass $pass, $key, $index, DataColumn $column) {
                                    return $column->grid->formatter->asCurrency($pass->price, $pass->currency);
                                },
                            ],
                            [
                                'class' => ActionColumn::className(),
                                'header' => 'actions',
                                'template' => '{update}{delete}',
                                'controller' => 'pass',
                            ],
                        ],
                    ],
                ]); ?>

                <?= Html::activeHiddenInput($newPass, 'event_id') ?>
                <?= $form->field($newPass, 'description')->textInput(['maxlength' => true]) ?>
                <?= $form->field($newPass, 'price')->textInput(['maxlength' => true]) ?>
                <?= $form->field($newPass, 'currency')->dropDownList(Yii::$app->params['currencies']) ?>
                <?= Html::submitButton("Add", ['class' => 'btn btn-danger']) ?>

                <?php GridForm::end() ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
