<?php

/* @var $this yii\web\View */
/* @var $mapForm MapForm */

use app\models\Dance;
use app\models\forms\MapForm;
use app\models\Group;
use app\widgets\AngularDancePicker;
use app\widgets\AngularToggleMore;
use app\widgets\assets\MultiAutoCompleteBundle;
use app\widgets\DateRangePicker;
use app\widgets\GeoSearch;
use app\widgets\MultiAutoComplete;
use app\widgets\PriceInput;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>

<?php if ($mapForm->isDirty()): ?>
    <div class="row list-nav-container">
        <div class="list-nav col-xs-4 col-md-3 col-lg-2 pull-right text-center">
            <span class="list-nav-clear" onclick="window.location = '<?= Url::to(["map"]) ?>'"><span class="glyphicon glyphicon-remove"></span> <?= Yii::t('app', "Clear") ?></span>
        </div>
        <div class="list-nav list-nav-message col-xs-8 col-md-9 col-lg-10">
            <?= Yii::t('app', "{n} event(s) found that match your search criteria.", ['n'=>count($mapForm->events)]) ?>
        </div>
    </div>
<?php endif; ?>
<div class="row filter-container text-center">
    <?php $form = ActiveForm::begin([
        'layout' => 'inline',
        // TODO: Change this form to GET method to avoid browser complaining on reload
    ]) ?>
    <div class="col-xs-12">
        <?= DateRangePicker::widget([
            'form' => $form,
            'model' => $mapForm,
            'fromAttr' => 'from_date',
            'toAttr' => 'to_date',
            'fieldOptions' => [
                'options' => [
                    'class' => 'form-group',
                ],
            ],
            'pickerConfig' => [
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ]) ?>
    </div>
    <div class="col-xs-12">
        <?php AngularDancePicker::begin([
            'generateNgApp' => false,
            'dances' => Dance::find()->all(),
            'selection' => count($mapForm->danceIds) ? $mapForm->danceIds : array_keys(Dance::find()->select('id')->indexBy('id')->all()),
        ]) ?>
        <div class="col-xs-6 text-right dance-picker">
            <span ng-repeat="dance in Picker.dances" ng-class="{'dance-btn-selected': dance.selected}" class="dance-btn" ng-click="dance.toggle()" title="{{dance.name}}">{{dance.getInitial()}}</span>
        </div>
        <div class="col-xs-6 text-left dance-picker">
                <span ng-if="Picker.allSelected() || Picker.noneSelected()">
                <?= Yii::t('app', "All dance styles") ?>
                    <input type="hidden" ng-repeat="dance in Picker.dances" name="MapForm[danceIds][]" ng-value="dance.id" />
                </span>
                <span ng-if="!Picker.allSelected() && !Picker.noneSelected()">
                    <?= Yii::t('app', "Only {{Picker.getSelectedDanceNames().join(', ')}}") ?>
                    <input type="hidden" ng-repeat="dance in Picker.getSelectedDances()" name="MapForm[danceIds][]" ng-value="dance.id" />
                </span>
        </div>
        <?php AngularDancePicker::end() ?>
    </div>
    <?php
    AngularToggleMore::begin([
        'isOpen'=> $mapForm->maxPrice || $mapForm->address || $mapForm->groupIds,
    ]) ?>
    <div class="col-xs-12">

        <div class="col-xs-12 filter-dropdown" ng-class="{'filter-open': Toggle.open}">
            <?= $form->field($mapForm, 'maxPrice')->widget(PriceInput::className(), [
                'options' => [
                    'placeholder' => $mapForm->getAttributeLabel('maxPrice'),
                    'ng-disabled' => '!Toggle.open',
                ],
            ]) ?>
            <?= $form->field($mapForm, 'address')->widget(GeoSearch::className(), [
                'currentLocationButton' => true,
                'lonInputOptions' => ['ng-disabled' => '!Toggle.open'],
                'latInputOptions' => ['ng-disabled' => '!Toggle.open'],
            ]) ?>
            <?= $form->field($mapForm, 'groupIds')->widget(MultiAutoComplete::className(), [
                'data' => ArrayHelper::map(Group::find()->orderBy('name')->all(), 'id', 'name'),
                'listBelow' => true,
                'autoCompleteConfig' => [
                    'options' => [
                        'placeholder' => Yii::t('app', "With artists..."),
                        'class' => 'form-control',
                    ],
                ],
            ]) ?>
        </div>

        <div class="more-filters-link pull-right">
            <a role="button" href="" ng-click="Toggle.toggle()" onclick="milpasos.multiAutoComplete.reset()" ng-cloak>
                <small ng-hide="Toggle.open"><span class="glyphicon glyphicon-chevron-down"></span><?= Yii::t('app', 'More options')?></small>
                <small ng-show="Toggle.open"><span class="glyphicon glyphicon-chevron-up"></span><?= Yii::t('app', 'Less options')?></small>
            </a>
        </div>
        <?= Html::submitButton(Yii::t('app', 'Search'), [
            'class' => 'btn btn-sm btn-default'
        ]) ?>

    </div>
    <?php AngularToggleMore::end() ?>
    <?php ActiveForm::end() ?>
</div>