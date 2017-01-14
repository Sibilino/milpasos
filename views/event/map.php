<?php

/* @var $this yii\web\View */
/* @var $mapForm app\models\forms\MapForm */

use app\models\Event;
use app\widgets\AngularEventViewer;
use app\widgets\DateRangePicker;
use app\widgets\MultiAutoComplete;
use sibilino\yii2\openlayers\OL;
use sibilino\yii2\openlayers\OpenLayers;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = 'Milpasos';

$features = array_map(function (Event $e) {
    return new OL('Feature', [
        'geometry' => new OL('geom.Point', new OL('proj.fromLonLat', [$e->lon,$e->lat])),
        'eventId' => $e->id,
    ]);
}, $mapForm->events);

?>

<div class="row content">
    <div class="col-lg-5 col-sm-6">
        <div class="row list-nav-container">
            <?php /* TODO: Implement navigation helper */ ?>
            <div class="list-nav list-nav-back col-xs-4 col-md-3 col-lg-2 text-center"><a> < </a></div>
            <div class="list-nav list-nav-message col-xs-8 col-md-9 col-lg-10">
                <?= count($mapForm->events) ?> events found. Click one to see more details.
            </div>
        </div>
        <div class="row filter-container text-center">
            <?php $form = ActiveForm::begin([
                'layout' => 'inline',
                // TODO: Change this form to GET method to avoid browser complaining on reload
            ]) ?>
            <div class="col-xs-12">
                    <?= DateRangePicker::widget([
                        'form' => $form,
                        'model' => $mapForm,
                        'fromAttr' => 'from_date',
                        'toAttr' => 'to_date',
                        'fieldOptions' => [
                            'options' => [
                                'class' => 'form-group',
                            ],
                        ],
                        'pickerConfig' => [
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ]) ?>
            </div>
            <div class="col-xs-12">
                S B K (All styles selected) <?php /* TODO: Implement dance picker */ ?>
            </div>
            <div class="col-xs-12">
                <div class="more-filters-link pull-right">
                    <a><small><?= Yii::t('app', 'More options...')?></small></a>
                </div>
                <?= Html::submitButton(Yii::t('app', 'Apply filters'), [
                    'class' => 'btn btn-sm btn-default'
                ]) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>

        <div class="row map-list">
            <?= AngularEventViewer::widget([
                'events' => $mapForm->events,
                'onSelect' => 'milpasos.eventMap.onSelect',
            ]) ?>
        </div>

        <div class="list-footer">
            &copy; Luis Hernández 2017
        </div>

    </div>
    <div class="col-lg-7 col-sm-6 hidden-xs map">
        <?= OpenLayers::widget([
            'id' => 'main-map',
            'options' => [
                'class' => 'event-map',
            ],
            'mapOptionScript' => Url::to('@web/js/mapOptions.js'),
            'mapOptions' => [
                'layers' => [
                    'Tile' => [
                        'source' => new OL('source.OSM'),
                    ],
                    'Vector' => [
                        'source' => new OL('source.Cluster', [
                            'distance' => 35,
                            'source' => new OL('source.Vector', [
                                'features' => $features,
                            ]),
                        ]),
                        'style' => new JsExpression("function(feature, resolution) {
                    return [
                        ".new OL('style.Style', [
                                'image' => new OL('style.Circle', [
                                    'radius' => 12,
                                    'stroke' => new OL('style.Stroke', [
                                        'color' => '#FFFFFF',
                                    ]),
                                    'fill' => new OL('style.Fill', [
                                        'color' => '#5d3082',
                                    ]),
                                ]),
                                'text' => new OL('style.Text', [
                                    'text' => new JsExpression('feature.get("features").length.toString()'),
                                    'fill' => new OL('style.Fill', [
                                        'color' => '#FFFFFF',
                                    ]),
                                    'font' => '12px Helvetica',
                                ]),
                            ])."
                    ];
                }"),
                    ],
                ],
                'view' => [
                    'center' => new OL('proj.fromLonLat', [6.62232,46.5235]),
                    'zoom' => 4,
                ],
            ],
        ]) ?>

    </div>
</div>
