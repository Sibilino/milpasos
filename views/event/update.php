<?php

use app\models\Pass;
use app\widgets\GridForm;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $newLink app\models\Link */
/* @var $newPass app\models\Pass */
/* @var $prices app\models\TemporaryPrice[] */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Event',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-4">
            <?= $this->render('_form', [
                'model' => $model,
                'newLink' => $newLink,
            ]) ?>
        </div>

        <div class="col-md-8">
            
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

            <?= $this->render('/pass/_form', [
                'model' => $newPass,
                'prices' => $prices,
                'form' => $form,
            ]) ?>
            
            <?php GridForm::end() ?>

        </div>

    </div>

</div>
