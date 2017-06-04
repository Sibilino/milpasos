<?php

/* @var $mapForm MapForm */

use app\models\Dance;
use app\models\forms\MapForm;
use app\models\Group;
use app\widgets\AngularDancePicker;
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
            'selection' => $mapForm->danceIds,
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
        <div class="col-xs-12 collapse" id="more-options">
            <?= $form->field($mapForm, 'maxPrice')->widget(PriceInput::className(), [
                'options' => ['placeholder' => $mapForm->getAttributeLabel('maxPrice')],
            ]) ?>
            <?= $form->field($mapForm, 'address')->widget(GeoSearch::className(), ['currentLocationButton' => true]) ?>
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
        <?php AngularDancePicker::end() ?>
    </div>
    <div class="col-xs-12">
        <div class="more-filters-link pull-right">
            <a data-toggle="collapse" role="button" href="#" data-target="#more-options" onclick="$(this).children().toggleClass('hidden')">
                <small><span class="glyphicon glyphicon-chevron-down"></span><?= Yii::t('app', 'More options')?></small>
                <small class="hidden"><span class="glyphicon glyphicon-chevron-up"></span><?= Yii::t('app', 'Less options')?></small>
            </a>
        </div>
        <?= Html::submitButton(Yii::t('app', 'Search'), [
            'class' => 'btn btn-sm btn-default'
        ]) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>