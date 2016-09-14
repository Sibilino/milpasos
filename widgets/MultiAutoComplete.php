<?php

namespace app\widgets;


use app\widgets\assets\MultiAutoCompleteBundle;
use yii\base\InvalidValueException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\AutoComplete;
use yii\widgets\InputWidget;

class MultiAutoComplete extends InputWidget
{
    public $data = []; // id => label

    public $listOptions = [];

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

        $defaults = [
            'name' => "$this->id-auto-complete",
            'clientOptions' => [
                'minLength' => 0,
                'source' => $this->toLabelValues($this->data),
            ],
        ];
        $this->options = ArrayHelper::merge($defaults, $this->options);


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
        $html = '<div>';
        $html .= Html::ul([], $this->listOptions);
        $html .= AutoComplete::widget($this->options);
        $html .= '</div>';

        MultiAutoCompleteBundle::register($this->view);
        $this->view->registerJs($this->getJs());

        return $html;
    }

    private function toLabelValues(array $data)
    {
        $result = [];
        foreach ($data as $value => $label) {
            $result []= [
                'label' => $label,
                'value' => $value,
            ];
        }
        return $result;
    }

    private function getInputName() {
        return $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;
    }

    private function getJs()
    {
        $autoCompleteId = Json::encode($this->options['id']);
        $inputName = Json::encode($this->getInputName().'[]');
        return "milpasos.multiAutoComplete.construct($autoCompleteId, $inputName);";
    }


}