<?php

/* @var $this yii\web\View */

use app\models\Event;
use sibilino\yii2\openlayers\OL;
use sibilino\yii2\openlayers\OpenLayers;
use sibilino\yii2\openlayers\OpenLayersBundle;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = 'Milpasos';

$features = array_map(function (Event $e) {
    return new OL('Feature', [
        'geometry' => new OL('geom.Point', new JsExpression("ol.proj.fromLonLat([$e->lon,$e->lat])")),
        'data' => Json::encode($e),
    ]);
}, Event::find()->all());

?>
<div class="site-index">

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