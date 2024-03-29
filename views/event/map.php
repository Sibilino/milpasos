<?php

/* @var $this yii\web\View */
/* @var $mapForm app\models\forms\MapForm */

use app\assets\AngularJsAsset;
use app\models\Event;
use app\widgets\AngularEventViewer;
use sibilino\yii2\openlayers\OL;
use sibilino\yii2\openlayers\OpenLayers;
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
    <div class="col-lg-4 col-sm-6" ng-app="<?= AngularJsAsset::ANGULAR_APP_NAME ?>">
        
        <?= $this->render('_eventFilters', array(
            'mapForm' => $mapForm,
        )) ?>

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
    <div class="col-lg-8 col-sm-6 hidden-xs map">
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
