<?php

/* @var $this yii\web\View */
/* @var $model app\models\forms\MapForm */
/* @var $events app\models\Event[] */

use app\models\Event;
use app\models\Dance;
use app\models\Group;
use app\widgets\DateRangePicker;
use app\widgets\GridForm;
use sibilino\yii2\openlayers\OL;
use sibilino\yii2\openlayers\OpenLayers;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
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
}, $events);

?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-inline',
        ],
    ]); ?>

    <div>

        <?= DateRangePicker::widget([
            'form' => $form,
            'model' => $model,
            'fromAttr' => 'from_date',
            'toAttr' => 'to_date',
        ]) ?>

        <?= $form->field($model, 'maxPrice')->input('number') ?>
    </div>
    <div>
        <?= $form->field($model, 'groupIds')->dropDownList(ArrayHelper::map(Group::find()->orderBy('name')->all(), 'id', 'name'), ['multiple'=>true]) ?>
        <?= $form->field($model, 'danceIds')->dropDownList(ArrayHelper::map(Dance::find()->orderBy('name')->all(), 'id', 'name'), ['multiple'=>true]) ?>

        <?= Html::submitButton(Yii::t('app', 'Search'), [
            'class' => 'btn btn-primary',
        ]) ?>
    </div>

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
        'options' => [
            'style' => ['display' => 'none'],
        ],
        'gridOptions' => [
            'emptyText' => Yii::t('app', 'Select an event on the map.'),
            'dataProvider' => new ActiveDataProvider([
                'query' => Event::find()->where(['id'=>$model->eventIds]),
                'sort' => [
                    'attributes' => ['start_date', 'end_date'],
                    'defaultOrder' => ['start_date'=>SORT_ASC],
                ],
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]),
            'columns' => [
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
                    'label' => Yii::t('app', "Min. price"),
                    'value' => function (Event $e) {
                        $minPass = $e->getPasses()->orderBy('price ASC')->one();
                        return $minPass === null ? null : Yii::$app->formatter->asCurrency($minPass->price, $minPass->currency);
                    }
                ],
            ],
        ],
    ]) ?>

    <?= $form->field($model, 'eventIds')
        ->hiddenInput(['value'=>implode(',',$model->eventIds)])
        ->label(false)->error(false) ?>

    <?php GridForm::end() ?>

</div>