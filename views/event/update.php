<?php

/* @var $this yii\web\View */
use app\models\Pass;
use app\widgets\ListForm;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $event app\models\Event */
/* @var $newLink app\models\Link */
/* @var $pass app\models\Pass */
/* @var $prices app\models\TemporaryPrice[] */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Event',
]) . ' ' . $event->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $event->name, 'url' => ['view', 'id' => $event->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="event-update">
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false, // So that "new pass" or "new price" empty fields do not show errors
    ]); ?>
    
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $event,
                        'newLink' => $newLink,
                        'form' => $form,
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <h2><?= Yii::t('app', "Passes") ?></h2>
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul>
                        <?php
                            $list = ListForm::begin([
                                'itemView' => function (Pass $model, $key, $index, ListForm $widget) {
                                    $link = Html::a(Html::encode(Html::getAttributeValue($model, 'description')), $widget->getOpenUrl($key));
                                    return Html::tag('li', $link);
                                },
                                'openParam' => 'selectedPassId',
                                'formView' => '/pass/_form',
                                'formViewParams' => [
                                    'prices' => $prices,
                                    'form' => $form,
                                ],
                                'dataProvider' => new ActiveDataProvider([
                                    'query' => $event->getPasses(),
                                ]),
                            ]);
                            ListForm::end();
                        ?>

                        <?php if ($list->hasOpenModel()):?>
                            <li>
                                <?= Html::a(Yii::t('app', "Add a new pass"), $list->getCloseUrl()) ?>
                            </li>
                        <?php else: ?>
                            <li>
                                <?= Yii::t('app', "New pass") ?>



                                <?= $this->render('/pass/_form', [
                                    'model' => $pass,
                                    'form' => $form,
                                ]) ?>


                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-3">


            <h2><?= Yii::t('app', "Temporary Prices") ?></h2>

            <?= $this->render('/temporary-price/_list', [
                'prices' => $prices,
                'form' => $form,
            ]) ?>


        </div>

    </div>

    <?php ActiveForm::end(); ?>
</div>
