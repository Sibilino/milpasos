<?php

namespace app\widgets;

use app\widgets\assets\DancePickerBundle;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Creates a div with an Angular controller that controls dance style selectors.
 * This widget does not output any input, only the controller's div and options.
 * @package app\widgets
 */
class AngularDancePicker extends Widget
{
    /**
     * @var string The name to be given to the controller instance in its Angular scope.
     */
    public $controllerAs = 'Picker';

    /**
     * @var array HTML options for the container div.
     */
    public $options = [];
    /**
     * @var array An array of Dance objects, containing a name and an id field.
     */
    public $dances = [];
    /**
     * @var array An array of integers, representing the id of the Dances to be initially selected.
     */
    public $selection = [];
    /**
     * @var bool Whether to generate the ng-app property in the container div.
     */
    public $generateNgApp = true;
    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();

        $jsDances = Json::encode($this->dances);
        $jsSelection = Json::encode($this->selection);
        $this->options = ArrayHelper::merge([
            'ng' => [
                'controller' => "DancePicker as $this->controllerAs",
                'cloak' => true,
                'init' => "$this->controllerAs.initDances($jsDances, $jsSelection)",
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