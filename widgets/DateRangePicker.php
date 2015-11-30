<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * Class DateRangePicker
 * @package app\widgets
 */
class DateRangePicker extends Widget
{
    /**
     * @var ActiveForm The ActiveForm containing this widget.
     */
    public $form;
    /**
     * @var Model
     */
    public $model;
    /**
     * @var string The attribute to be used in the "from" input.
     */
    public $fromAttr;
    /**
     * @var string The attribute to be used in the "to" input.
     */
    public $toAttr;
    /**
     * @var array Options to apply to BOTH datepicker sub-widgets.
     */
    public $options = [
        'dateFormat' => 'yyyy-MM-dd',
    ];
    /**
     * @var array Options to use as clientOptions in the "from" datepicker.
     */
    public $fromOptions = [];
    /**
     * @var array Options to use as clientOptions in the "to" datepicker.
     */
    public $toOptions = [];
    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();
        if (!($this->form && $this->model && $this->fromAttr && $this->toAttr)) {
            throw new InvalidConfigException("The 'form', 'model', 'fromAttr' and 'toAttr' properties are required.");
        }
    }

    public function run()
    {
        $idFrom = Html::getInputId($this->model, $this->fromAttr);
        $idTo = Html::getInputId($this->model, $this->toAttr);

        $fromOptions = array_merge([
            'maxDate' => $this->model->{$this->toAttr},
            'onClose' => new JsExpression("function () {
                $(\"#$idTo\").datepicker(\"option\", \"minDate\", $(\"#$idFrom\").datepicker(\"getDate\"));
            }"),
        ], $this->fromOptions);
        echo $this->form->field($this->model, $this->fromAttr)->widget(DatePicker::className(), ArrayHelper::merge(
            $this->options, ['clientOptions' => $fromOptions]
        ));

        $toOptions = array_merge([
            'minDate' => $this->model->{$this->fromAttr},
            'onClose' => new JsExpression("function () {
                $(\"#$idFrom\").datepicker(\"option\", \"maxDate\", $(\"#$idTo\").datepicker(\"getDate\"));
            }")
        ], $this->toOptions);
        echo $this->form->field($this->model, $this->toAttr)->widget(DatePicker::className(), ArrayHelper::merge(
            $this->options, ['clientOptions' => $toOptions]
        ));
    }

}