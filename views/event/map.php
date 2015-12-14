<?php

/* @var $this yii\web\View */
/* @var $model app\models\EventSearch */
/* @var $provider yii\data\ActiveDataProvider */

use app\models\Event;
use app\models\EventSearch;
use app\widgets\DateRangePicker;
use app\widgets\GridForm;
use sibilino\yii2\openlayers\OL;
use sibilino\yii2\openlayers\OpenLayers;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = 'Milpasos';

$features = array_map(function (Event $e) {
    return new OL('Feature', [
        'geometry' => new OL('geom.Point', new OL('proj.fromLonLat', [$e->lon,$e->lat])),
        'eventId' => $e->id,
    ]);
}, $provider->getModels());

?>
<div class="site-index">
    
    <div>
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'form-inline',
            ],
        ]); ?>
        
        <?= DateRangePicker::widget([
            'form' => $form,
            'model' => $model,
            'fromAttr' => 'start_date',
            'toAttr' => 'end_date',
        ]) ?>
        
        <?= Html::submitButton(Yii::t('app', 'Update'), [
            'class' => 'btn btn-primary',
        ]) ?>
        
        <?php ActiveForm::end(); ?>
    </div>
    
    <?= OpenLayers::widget([
        'id' => 'main-map',
        'mapOptionScript' => Url::to('@web/js/mapOptions.js'),
        'mapOptions' => [
            'layers' => [
                'Tile' => [
                    'source' => new OL('source.MapQuest', [
                        'layer' => 'sat',
                    ]),
                ],
                'Vector' => [
                    'source' => new OL('source.Cluster', [
                        'distance' => 30,
                        'source' => new OL('source.Vector', [
                            'features' => $features,
                        ]),
                    ]),
                    'style' => new JsExpression("function(feature, resolution) {
                        return [
                            ".new OL('style.Style', [
                                'image' => new OL('style.Circle', [
                                    'radius' => 10,
                                    'stroke' => new OL('style.Stroke', [
                                        'color' => '#FFFFFF',
                                    ]),
                                    'fill' => new OL('style.Fill', [
                                        'color' => '#3399CC',
                                    ]),
                                ]),
                                'text' => new OL('style.Text', [
                                    'text' => new JsExpression('feature.get("features").length.toString()'),
                                    'fill' => new OL('style.Fill', [
                                        'color' => '#FFFFFF',
                                    ]),
                                ]),
                            ])."
                        ];
                    }"),
                ],
            ],
            'view' => [
                'center' => new OL('proj.fromLonLat', [6.62232,46.5235]),
                'zoom' => 2,
            ],
        ],
    ]) ?>

    <div class="body-content">

        <?php $form = GridForm::begin([
            'id' => 'selection-form',
            'method' => 'get',
            'gridOptions' => [
                'dataProvider' => new ActiveDataProvider([
                    'query' => $provider->query,
                    'sort' => [
                        'attributes' => ['start_date', 'end_date'],
                        'defaultOrder' => ['start_date'=>SORT_ASC],
                    ],
                    'pagination' => [
                        'pageSize' => 5,
                    ],
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    'start_date:date',
                    'end_date:date',
                    'address',
                ],
            ],
        ]) ?>

        <?= $form->field($model, 'ids')
            ->hiddenInput(['value'=>implode(',',$model->ids)])
            ->label(false)->error(false) ?>

        <?php GridForm::end() ?>

    </div>
</div>
