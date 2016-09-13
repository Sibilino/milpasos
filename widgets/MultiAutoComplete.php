<?php

namespace app\widgets;


use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\InputWidget;

class MultiAutoComplete extends InputWidget
{
    public $initialSelection = [];

    public $listOptions = [];
    public $autoCompleteOptions = [];

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
    }


    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        $this->view->registerJs($this->getJs());
        $html = Html::ul($this->initialSelection, $this->listOptions);
        $html .= AutoComplete::widget($this->autoCompleteOptions);

        return $html;
    }

    private function getInputName() {
        return $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;
    }

    private function getJs()
    {
        $autoCompleteId = $this->autoCompleteOptions['id'];
        $listId = $this->listOptions['id'];
        $inputName = $this->getInputName().'[]';
        return "
            $('#$autoCompleteId').on('autocompleteselect', function (event, ui) {
                
                var li = $('<li>').attr('id', ui.item.value).text(ui.item.label);
                var input = $('<input type=\'hidden\' name=\'$inputName\' />').val(ui.item.value);
            
                li.append(input);
                li.on('click', function () {
                    var source = $('#$autoCompleteId').autocomplete('option', 'source');
                    source.push({
                        value: $(this).attr('id'),
                        label: $(this).text()
                    });
                    $('#$autoCompleteId').autocomplete('option', 'source', source);
                    $(this).remove();
                });
                
                $('#$listId').append(li);
                var newSource = $(this).autocomplete('option', 'source');
                var newSource = $.grep(newSource, function (e) {
                    return e.value != ui.item.value;
                });
                $(this).autocomplete('option', 'source', newSource);
            });
            $('#$autoCompleteId').on('click', function (event) {
                $(this).autocomplete('search'); // open menu
            });
            $('#$autoCompleteId').on('focusout', function (event) {
                $(this).autocomplete('close');
            });
            $('#$autoCompleteId').on('autocompleteclose', function (event, ui) {
                $(this).val('');
            });
        ";
    }


}