<?php

/* @var $this yii\web\View */
/* @var $mapForm app\models\forms\MapForm */
/* @var $listForm app\models\forms\EventListForm */

use app\models\Event;
use sibilino\yii2\openlayers\OL;
use sibilino\yii2\openlayers\OpenLayers;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ListView;

$this->title = 'Milpasos';

$features = array_map(function (Event $e) {
    return new OL('Feature', [
        'geometry' => new OL('geom.Point', new OL('proj.fromLonLat', [$e->lon,$e->lat])),
        'eventId' => $e->id,
    ]);
}, $mapForm->events);

?>

<div class="row content">

    <div class="col-lg-5 map-list">
        <?= ListView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => Event::find()->where(['id'=>$listForm->eventIds]),
                'sort' => [
                    'attributes' => ['start_date', 'end_date'],
                    'defaultOrder' => ['start_date'=>SORT_ASC],
                ],
            ]),
            'itemView' => '_listRow',
            'summary' => '',
        ]) ?>
    </div>

    <div class="col-lg-7">
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
                    'zoom' => 5,
                ],
            ],
        ]) ?>

    </div>


</div>