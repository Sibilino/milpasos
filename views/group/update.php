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
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="group-update padded">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $this->render('_fields', [
                'model' => $model,
                'form' => $form,
            ]) ?>

        </div>
        <div class="col-md-9">
            <ul>
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

                <?php if ($list->hasOpenModel()):?>
                    <li>
                        <?= Html::a(Yii::t('app', "Add a new Artist"), $list->getCloseUrl(), [
                            'class' => 'text-danger',
                        ]) ?>
                    </li>
                <?php else: ?>
                    <li>
                        <span class="text-danger"><?= Yii::t('app', "New Artist") ?></span>

                        <div class="well">
                            <?= $this->render('/artist/_fields', [
                                'model' => $artist,
                                'form' => $form,
                            ]) ?>
                        </div>
                    </li>
                <?php endif; ?>

            </ul>
            
        </div>
        
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
