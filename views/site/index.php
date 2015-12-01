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
                '.new OL('interaction.Select', [
                    'style' => new JsExpression('function(feature, resolution) {
                        return [
                            '.new OL('style.Style', [
                                'image' => new OL('style.Circle', [
                                    'radius' => 10,
                                    'fill' => new OL('style.Fill', [
                                        'color' => '#FF0000',
                                    ]),
                                    'stroke' => new OL('style.Stroke', [
                                        'color' => "#000000",
                                    ]),
                                ]),
                                'text' => new OL('style.Text', [
                                    'text' => new JsExpression("feature.get('features').length.toString()"),
                                    'fill' => new OL('style.Fill', [
                                        'color' => '#FFFFFF',
                                    ]),
                                ]),
                            ]).',
                        ];
                    }'),
                ]) .'
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
