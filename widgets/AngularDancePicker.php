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
        parent::init();

        $this->options = ArrayHelper::merge([
            'ng' => [
                'controller' => "DancePicker as $this->controllerAs",
                'cloak' => true,
            ],
        ], $this->options);

        if ($this->generateNgApp) {
            $this->options['ng']['app'] = static::GetAngularAppName();
        }

        DancePickerBundle::register($this->view);

        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        $content = ob_get_clean();
        return Html::tag('div', $content, $this->options);
    }

    /**
     * @return string Returns the angular app name to be used when placing an ng-app to contain this widget.
     */
    public static function GetAngularAppName() {
        return DancePickerBundle::GetAngularAppName();
    }
}