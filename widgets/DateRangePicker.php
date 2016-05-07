<?php

namespace app\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/**
 * Class DateRangePicker is a widget that outputs two linked datepickers for a model within an ActiveForm.
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
     * @var array Config to apply to BOTH datepicker sub-widgets, upon its construction.
     */
    public $pickerConfig = [
        'dateFormat' => 'yyyy-MM-dd',
    ];
    /**
     * @var array The HTML options to apply to BOTH datepicker form field containers.
     */
    public $fieldOptions = [];
    /**
     * @var array Options to use as clientOptions in the "from" datepicker.
     */
    public $fromOptions = [];
    /**
     * @var array Options to use as clientOptions in the "to" datepicker.
     */
    public $toOptions = [];
    /**
     * @var string The mask to be used for the date inputs. Defaults to "9999-99-99". Set to false to disable masking.
     */
    public $mask = "9999-99-99";
    /**
     * @var string The placeholder to be used for the date inputs (before translation). Defaults to "yyyy-mm-dd".
     */
    public $placeholder = "yyyy-mm-dd";

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
        if (!isset($this->fromOptions['id'])) {
            $this->fromOptions['id'] = Html::getInputId($this->model, $this->fromAttr);
        }
        if (!isset($this->toOptions['id'])) {
            $this->toOptions['id'] = Html::getInputId($this->model, $this->toAttr);
        }
    }

    public function run()
    {
        $idFrom = $this->fromOptions['id'];
        $idTo = $this->toOptions['id'];

        $defaults = [
            'maxDate' => Html::getAttributeValue($this->model, $this->toAttr),
            'onClose' => new JsExpression("function () {
                $(\"#$idTo\").datepicker(\"option\", \"minDate\", $(\"#$idFrom\").datepicker(\"getDate\"));
            }"),
        ];
        $fromOptions = array_merge($defaults, $this->fromOptions);
        $this->addMask($fromOptions);

        echo $this->form->field($this->model, $this->fromAttr, $this->fieldOptions)->widget(DatePicker::className(), ArrayHelper::merge(
            $this->pickerConfig, ['clientOptions' => $fromOptions]
        ));

        $defaults = [
            'minDate' => Html::getAttributeValue($this->model, $this->fromAttr),
            'onClose' => new JsExpression("function () {
                $(\"#$idFrom\").datepicker(\"option\", \"maxDate\", $(\"#$idTo\").datepicker(\"getDate\"));
            }")
        ];
        $toOptions = array_merge($defaults, $this->toOptions);
        $this->addMask($toOptions);

        echo $this->form->field($this->model, $this->toAttr, $this->fieldOptions)->widget(DatePicker::className(), ArrayHelper::merge(
            $this->pickerConfig, ['clientOptions' => $toOptions]
        ));
    }

    /**
     * Processes an input's options to add a mask plugin that makes the expected format clear to the user.
     * @param array $options
     */
    private function addMask(array &$options) {
        if ($this->mask !== false) {
            $maskedInput = new MaskedInput([
                'id' => $options['id'],
                'name'=>'unused',
                'mask'=> $this->mask,
                'clientOptions' => ['placeholder' => Yii::t('app', $this->placeholder)],
            ]);
            $maskedInput->registerClientScript();
            $maskDataAttr = 'data-plugin-'.MaskedInput::PLUGIN_NAME;
            $options[$maskDataAttr] = $maskedInput->options[$maskDataAttr];
        }
    }
}