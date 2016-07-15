<?php

/* @var $this yii\web\View */
use app\models\Pass;
use app\widgets\ListForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

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
    
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'newLink' => $newLink,
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-body">

                    <?= ListForm::widget([
                        'itemView' => function (Pass $model, $key, $index, ListForm $widget) {
                            return Html::a(Html::encode(Html::getAttributeValue($model, 'description')), $widget->getOpenUrl($key));
                        },
                        'formView' => '/pass/_form',
                        'formViewParams' => [
                            'prices' => $prices,
                        ],
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getPasses(),
                        ]),
                    ]) ?>

                </div>
            </div>
        </div>

    </div>

</div>
