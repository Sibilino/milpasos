<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\InputWidget;

class DateRangePicker extends InputWidget
{
    public $endAttribute;

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        if (!$this->endAttribute) {
            throw new InvalidConfigException("The 'endAttribute' property must be configured.");
        }
        if ($this->hasModel() && $this->model->hasErrors($this->endAttribute)) {
            foreach ($this->model->getErrors($this->endAttribute) as $error) {
                $this->model->addError($this->attribute, $error);
            }

        }
        parent::init();
    }

    public function run()
    {
        echo DatePicker::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
        ]);
        echo DatePicker::widget([
            'model' => $this->model,
            'attribute' => $this->endAttribute,
        ]);
    }


}