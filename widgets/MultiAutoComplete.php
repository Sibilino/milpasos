<?php

namespace app\widgets;


use app\widgets\assets\MultiAutoCompleteBundle;
use yii\base\InvalidValueException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\AutoComplete;
use yii\widgets\InputWidget;

/**
 * This widgets shows an input consisting in a searchable dropdown with selectable items,
 * and a list of the current selection. Clicking on items adds or removes them to the list.
 * @property array $options Options passed to the underlying autocomplete input. Source will be overwritten by $data.
 * @package app\widgets
 */
class MultiAutoComplete extends InputWidget
{
    /**
     * @var array value => label array of all possible items for selection. Values must be unique.
     */
    public $data = [];

    /**
     * @var array Options to be applied to the html list.
     * @see yii\helpers\Html::ul()
     */
    public $listOptions = [];

    /**
     * @var array The values stored in the given model's attribute.
     */
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
            ],
        ];
        $this->options = ArrayHelper::merge($defaults, $this->options);
        $this->options['clientOptions']['source'] = $this->toLabelValues($this->data);


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
        $html .= Html::hiddenInput($this->getInputName()); // To allow saving empty selection
        $html .= Html::ul([], $this->listOptions);
        $html .= AutoComplete::widget($this->options);
        $html .= '</div>';

        MultiAutoCompleteBundle::register($this->view);
        $this->view->registerJs($this->getJs());

        return $html;
    }

    /**
     * Gets an array of label => value and turns it into array of arrays with 'label' => label and 'value' => value.
     * @param array $data
     * @return array
     */
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

    /**
     * @return string Returns the name to be used for inputs.
     */
    private function getInputName() {
        return $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;
    }

    /**
     * @return string The JavaScript needed to activate this widget.
     */
    private function getJs()
    {
        $autoCompleteId = Json::encode($this->options['id']);
        $inputName = Json::encode($this->getInputName().'[]');
        $selection = Json::encode($this->_modelValue);
        return "milpasos.multiAutoComplete.activate($autoCompleteId, $inputName, $selection);";
    }


}