<?php

/* @var $this yii\web\View */

use app\models\Event;
use sibilino\yii2\openlayers\OL;
use sibilino\yii2\openlayers\OpenLayers;
use yii\helpers\Json;
use yii\web\JsExpression;

$this->title = 'Milpasos';

$coords = array_map(function (Event $e) { return [$e->lon, $e->lat]; }, Event::find()->all());
$features = array_map(function ($c) {
    return new OL('Feature', new OL('geom.Point', new OL('proj.fromLonLat', $c)));
}, $coords);

?>
<div class="site-index">

    <?= OpenLayers::widget([
        'mapOptions' => [
            'interactions' => new JsExpression('ol.interaction.defaults().extend([
                new ol.interaction.Select({
                    style: function(feature, resolution) {
                        return [
                            new ol.style.Style({
                                image: new ol.style.Circle({
                                    radius: 10,
                                    fill: new ol.style.Fill({
                                        color: "#FF0000"
                                    }),
                                    stroke: new ol.style.Stroke({
                                        color: "#000000"
                                    })
                                }),
                                text: new ol.style.Text({
                                    text: feature.get("features").length.toString(),
                                    fill: new ol.style.Fill({
                                        color: "#fff"
                                    })
                                })
                            })
                        ];
                    }
                 })
            ])'),
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
                          return [new ol.style.Style({
                            image: new ol.style.Circle({
                              radius: 10,
                              stroke: new ol.style.Stroke({
                                color: '#fff'
                              }),
                              fill: new ol.style.Fill({
                                color: '#3399CC'
                              })
                            }),
                            text: new ol.style.Text({
                              text: feature.get('features').length.toString(),
                              fill: new ol.style.Fill({
                                color: '#fff'
                              })
                            })
                          })];
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

        <div class="row">
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4">
            </div>
        </div>

    </div>
</div>
