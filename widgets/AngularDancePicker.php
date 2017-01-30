<?php

namespace app\widgets;

use app\widgets\assets\DancePickerBundle;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class AngularDancePicker extends Widget
{
    public $controllerAs = 'Picker';

    public $options = [];
    public $generateNgApp = true;
    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        if ($this->generateNgApp) {
            $this->options = ArrayHelper::merge([
                'ng' => [
                    'app' => static::GetAngularAppName(),
                    'controller' => "DancePicker as $this->controllerAs",
                ],
            ], $this->options);
        }
        DancePickerBundle::register($this->view);
        parent::init();
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        return Html::tag('div', '', $this->options);
    }

    /**
     * @return string Returns the angular app name to be used when placing an ng-app to contain this widget.
     */
    public static function GetAngularAppName() {
        return DancePickerBundle::GetAngularAppName();
    }
}