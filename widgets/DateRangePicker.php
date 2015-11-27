<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
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