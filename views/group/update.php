<?php

use app\models\Artist;
use app\widgets\ListForm;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Group */
/* @var $artist app\models\Artist */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Group',
]) . ' ' . $model->name;
?>
<div class="group-update padded">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-danger pull-left top-left-button']) ?>
    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row">

        <div class="col-md-9 col-md-push-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title"><?= Yii::t('app', "Artists in Group") ?></h2>
                </div>
                <div class="panel-body listform">
                        <?php
                        $view = $this;
                        $list = ListForm::begin([
                            'summary' => '',
                            'itemView' => function (Artist $model, $key, $index, ListForm $widget) {
                                $link = Html::a(Html::encode(Html::getAttributeValue($model, 'name')), $widget->getOpenUrl($key));
                                return Html::tag('li', $link);
                            },
                            'openParam' => 'selectedArtistId',
                            'formView' => function (Artist $model) use ($view, $form) {
                                $formHtml = $view->render('/artist/_fields', [
                                    'model' => $model,
                                    'form' => $form,
                                ]);
                                return Html::tag('li', $formHtml);
                            },
                            'dataProvider' => new ActiveDataProvider([
                                'query' => $model->getArtists(),
                            ]),
                        ]);
                        ListForm::end();
                        ?>

                        <div data-key="0">
                            <?php if ($list->hasOpenModel()):?>
                                <?= Html::a(Yii::t('app', "Add a new Artist"), $list->getCloseUrl(), [
                                    'class' => 'text-danger',
                                ]) ?>
                            <?php else: ?>
                                <span class="text-danger"><?= Yii::t('app', "New Artist") ?></span>

                                <?= $this->render('/artist/_fields', [
                                    'model' => $artist,
                                    'form' => $form,
                                ]) ?>
                            <?php endif; ?>
                        </div>

                </div>
            </div>

        </div>

        <div class="col-md-3 col-md-pull-9">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title"><?= Yii::t('app', "Group") ?></h2>
                </div>
                <div class="panel-body">
                    <?= $this->render('_fields', [
                        'model' => $model,
                        'form' => $form,
                    ]) ?>
                </div>
            </div>

        </div>
        
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-danger']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
