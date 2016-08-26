<?php

/* @var $this yii\web\View */
/* @var $mapForm app\models\forms\MapForm */
/* @var $listForm app\models\forms\EventListForm */

use app\models\Event;
use app\models\Dance;
use app\models\Group;
use app\widgets\DateRangePicker;
use app\widgets\GeoSearch;
use app\widgets\GridForm;
use app\widgets\PriceInput;
use sibilino\yii2\openlayers\OL;
use sibilino\yii2\openlayers\OpenLayers;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;

$this->title = 'Milpasos';

$features = array_map(function (Event $e) {
    return new OL('Feature', [
        'geometry' => new OL('geom.Point', new OL('proj.fromLonLat', [$e->lon,$e->lat])),
        'eventId' => $e->id,
    ]);
}, $mapForm->events);

?>

<div class="row">
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


<div class="map-filters well">
    <div class="row">
        <div class="col-sm-3">
            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'horizontalCssClasses' => [
                        'offset' => 'col-xs-offset-4',
                        'label' => 'col-xs-4',
                        'wrapper' => 'col-xs-8',
                    ],
                ],
                'options' => [
                    'class' => 'compact',
                ],
            ]); ?>
    
            <div>
    
                <?= DateRangePicker::widget([
                    'form' => $form,
                    'model' => $mapForm,
                    'fromAttr' => 'from_date',
                    'toAttr' => 'to_date',
                    'pickerConfig' => [
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ]) ?>
    
            </div>
            <div>
                <?= $form->field($mapForm, 'groupIds')->dropDownList(ArrayHelper::map(Group::find()->orderBy('name')->asArray()->all(), 'id', 'name'), ['multiple'=>true]) ?>
                <?= $form->field($mapForm, 'danceIds')->dropDownList(ArrayHelper::map(Dance::find()->orderBy('name')->asArray()->all(), 'id', 'name'), ['multiple'=>true]) ?>
                <?= $form->field($mapForm, 'maxPrice')->widget(PriceInput::className()) ?>
                <?= $form->field($mapForm, 'address')->widget(GeoSearch::className(), [
                    'currentLocationButton' => true,
                    'showMap' => false,
                ]) ?>
                
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-4">
                        <?= Html::submitButton(Yii::t('app', 'Search'), [
                            'class' => 'btn btn-primary',
                        ]) ?>
                    </div>
                </div>
            </div>
    
            <?php ActiveForm::end(); ?>
        </div>
    
        <div class="col-sm-9">
            <?php $form = GridForm::begin([
                'method' => 'get',
                'options' => [
                    'style' => ['display' => 'none'],
                ],
                'gridOptions' => [
                    'tableOptions' => [
                        'class' => 'table table-condensed'
                    ],
                    'emptyText' => Yii::t('app', 'Select an event on the map.'),
                    'dataProvider' => new ActiveDataProvider([
                        'query' => Event::find()->where(['id'=>$listForm->eventIds]),
                        'sort' => [
                            'attributes' => ['start_date', 'end_date'],
                            'defaultOrder' => ['start_date'=>SORT_ASC],
                        ],
                        'pagination' => [
                            'pageSize' => 3,
                        ],
                    ]),
                    'columns' => [
                        'imageUrl:image',
                        [
                            'attribute' => 'name',
                            'value' => function (Event $e) {
                                return Html::a(Html::encode($e->name), Url::to(["event/view", 'id'=>$e->id]));
                            },
                            'format' => 'raw',
                        ],
                        'start_date:date',
                        'end_date:date',
                        'address',
                        [
                            'label' => Yii::t('app', "Dance styles"),
                            'value' => function (Event $e) {
                                return implode(', ', ArrayHelper::getColumn($e->dances, 'name'));
                            }
                        ],
                        [
                            'label' => Yii::t('app', "Price"),
                            'value' => function (Event $e) {
                                $price = $e->bestAvailablePrice();
                                if ($price !== null) {
                                    return Yii::$app->formatter->asCurrency($price->price, $price->currency);
                                }
                                return null;
                            },
                        ],
                    ],
                ],
            ]) ?>
        
            <?= $form->field($listForm, 'eventIds')
                ->hiddenInput([
                    'value'=>implode('-',$listForm->eventIds),
                    'id'=>'selection-input',
                ])->label(false)->error(false) ?>
        
            <?php GridForm::end() ?>
    
        </div>
    </div>
    
</div>
