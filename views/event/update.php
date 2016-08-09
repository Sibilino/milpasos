<?php

/* @var $this yii\web\View */
use app\models\Pass;
use app\widgets\AjaxReloadButton;
use app\widgets\ListForm;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
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
                'validateOnSubmit' => false,
            ]);
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="pull-right btn-group btn-group-xs">
                        <?= Html::submitButton(Yii::t('app', "Save"), [
                            'class' => 'btn btn-success',
                        ]) ?>
                    </div>
                    <h2 class="panel-title"><?= Yii::t('app', "Passes") ?></h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <?php
                                    $view = $this;
                                    $list = ListForm::begin([
                                        'summary' => '',
                                        'itemView' => function (Pass $model, $key, $index, ListForm $widget) {
                                            $price = Yii::$app->formatter->asCurrency($model->price, $model->currency);
                                            $link = Html::a(Html::encode(Html::getAttributeValue($model, 'description'). " ($price)"), $widget->getOpenUrl($key));
                                            return Html::tag('li', $link);
                                        },
                                        'openParam' => 'selectedPassId',
                                        'formView' => function (Pass $model) use ($view, $form, $prices) {
                                            $formHtml = $view->render('/pass/_fields', [
                                                'model' => $model,
                                                'prices' => $prices,
                                                'form' => $form,
                                            ]);
                                            $deleteBtn = Html::a(Yii::t('app', "Delete"), ['/pass/delete', 'id' => $model->id], [
                                                'class' => 'btn btn-danger btn-xs',
                                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                                'data-method' => 'post',
                                                'data-pjax' => 0,
                                            ]);
                                            $formHtml = Html::tag('div', $formHtml.$deleteBtn, ['class' => 'well']);
                                            $price = Yii::$app->formatter->asCurrency($model->price, $model->currency);
                                            $headerHtml = "<h4>".Html::encode(Html::getAttributeValue($model, 'description'))." ($price)</h4>";
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
                                        <?= Html::a(Yii::t('app', "Add a new pass"), $list->getCloseUrl(), [
                                            'class' => 'text-danger',
                                        ]) ?>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <span class="text-danger"><?= Yii::t('app', "New pass") ?></span>

                                        <div class="well">
                                        <?= $this->render('/pass/_fields', [
                                            'model' => $pass,
                                            'form' => $form,
                                        ]) ?>
                                        </div>


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

    </div>

</div>
