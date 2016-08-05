<?php

/* @var $this yii\web\View */
use app\models\Pass;
use app\widgets\ListForm;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $event app\models\Event */
/* @var $newLink app\models\Link */
/* @var $pass app\models\Pass */
/* @var $prices app\models\TemporaryPrice[] */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Event',
]) . ' ' . $event->name;
?>
<div class="event-update">
    <div class="row">
        <div class="col-md-6">

            <?php
            Pjax::begin();
            $form = ActiveForm::begin([
                'enableClientValidation' => false,
            ]);
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="pull-right btn-group btn-group-xs">
                        <?= Html::submitButton(Yii::t('app', "Save"), [
                            'class' => 'btn btn-success',
                        ]) ?>
                    </div>
                    <h2 class="panel-title"><?= $event->name ?></h2>
                </div>
                <div class="panel-body">
                    <?= $this->render('_fields', [
                        'model' => $event,
                        'newLink' => $newLink,
                        'form' => $form,
                    ]) ?>
                </div>
                <div class="panel-footer">
                    <div class="btn-group btn-group-xs">
                        <?= Html::submitButton(Yii::t('app', "Save"), [
                            'class' => 'btn btn-success',
                        ]) ?>
                    </div>
                </div>
            </div>

            <?php
            ActiveForm::end();
            Pjax::end();
            ?>
        </div>

        <div class="col-md-6">

            <?php
            Pjax::begin();
            $form = ActiveForm::begin([
                'enableClientValidation' => false,
            ]);
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title"><?= Yii::t('app', "Passes") ?></h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <?php
                                    $controller = $this;
                                    $list = ListForm::begin([
                                        'summary' => '',
                                        'itemView' => function (Pass $model, $key, $index, ListForm $widget) {
                                            $link = Html::a(Html::encode(Html::getAttributeValue($model, 'description')), $widget->getOpenUrl($key));
                                            return Html::tag('li', $link);
                                        },
                                        'openParam' => 'selectedPassId',
                                        'formView' => function (Pass $model) use ($controller, $form, $prices) {
                                            $formHtml = $controller->render('/pass/_form', [
                                                'model' => $model,
                                                'prices' => $prices,
                                                'form' => $form,
                                            ]);
                                            $formHtml = Html::tag('div', $formHtml, ['class' => 'well']);
                                            $headerHtml = "<h4>$model->description</h4>";
                                            return Html::tag('li', $headerHtml.$formHtml);
                                        },
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
                        <div class="col-md-6">

                            <?php if ($list->hasOpenModel()): ?>

                            <h4><?= Yii::t('app', "Price list for \"{passName}\":", ['passName' => $list->getOpenModel()->description]) ?></h4>

                            <?= $this->render('/temporary-price/_list', [
                                'prices' => $prices,
                                'form' => $form,
                            ]) ?>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

            <?php
            ActiveForm::end();
            Pjax::end();
            ?>

        </div>

    </div>

</div>
