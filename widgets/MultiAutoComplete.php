<?php

namespace app\widgets;


use app\widgets\assets\MultiAutoCompleteBundle;
use yii\base\InvalidValueException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\AutoComplete;
use yii\widgets\InputWidget;

class MultiAutoComplete extends InputWidget
{
    public $data = [];

    public $listOptions = [];
    public $autoCompleteOptions = [];

    private $_modelValue;

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();

        if (!isset($this->listOptions['id'])) {
            $this->listOptions['id'] = $this->getId().'-select-list';
        }
        if (!isset($this->autoCompleteOptions['id'])) {
            $this->autoCompleteOptions['id'] = "$this->id-auto-complete";
        }
        if (!isset($this->autoCompleteOptions['name'])) {
            $this->autoCompleteOptions['name'] = "$this->id-auto-complete";
        }
        if (!isset($this->autoCompleteOptions['value'])) {
            $this->autoCompleteOptions['value'] = '';
        }

        $this->_modelValue = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        if (!is_array($this->_modelValue)) {
            throw new InvalidValueException('The selection value must be an array.');
        }
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        $selection = [];
        foreach ($this->data as $value => $label) {
            if (in_array($value, $this->_modelValue)) {
                $selection []= $label;
            }
        }

        $html = '<div>';
        $html .= Html::ul($selection, $this->listOptions);
        $html .= AutoComplete::widget($this->autoCompleteOptions);
        $html .= '</div>';

        MultiAutoCompleteBundle::register($this->view);
        $this->view->registerJs($this->getJs());

        return $html;
    }

    private function getInputName() {
        return $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;
    }

    private function getJs()
    {
        $autoCompleteId = Json::encode($this->autoCompleteOptions['id']);
        $inputName = Json::encode($this->getInputName().'[]');
        return "milpasos.multiAutoComplete.construct($autoCompleteId, $inputName);";
    }
}