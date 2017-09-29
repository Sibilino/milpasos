<?php

namespace app\widgets;


use app\widgets\assets\ToggleMoreBundle;
use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This angular app controls a toggle variable that switches from true to false, to use in open/close html elements.
 * @package app\widgets
 */
class AngularToggleMore extends Widget
{
    /**
     * @var string The name to be given to the controller instance in its Angular scope.
     */
    public $controllerAs = 'Toggle';

    /**
     * @var bool Whether the toggle will start as open.
     */
    public $isOpen = false;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();

        ToggleMoreBundle::register($this->view);

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
        return Html::tag('div', $content, ['ng' => [
            'controller' => "ToggleMore as $this->controllerAs",
            'init' => "$this->controllerAs.init(".Json::encode((bool)$this->isOpen).")",
        ]]);
    }

}