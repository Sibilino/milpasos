<?php

namespace app\widgets;

use app\models\interfaces\IFormattedAttributes;
use app\widgets\assets\EventViewerAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * Generates an AngularJs application that controls a list of Events.
 *
 * @package app\widgets
 */
class AngularEventViewer extends Widget
{
    /**
     * @var array HTML options for the container DIV, which contains the AngularJs app.
     */
    public $options = [];
    /**
     * @var array HTML options for the inner DIV, which contains the AngularJs view.
     */
    public $viewDivOptions = [];
    /**
     * @var IFormattedAttributes[]
     */
    public $events = [];
    /**
     * Listener to be used for an Event selection event.
     * @var string A Javascript function that receives an array of Event ids.
     */
    public $onSelect = '';
    /**
     * @var bool Whether to add a default ng-app attribute to the widget container div.
     */
    public $generateNgApp = false;

    /**
     * Initializes ids and registers the necessary JavaScript.
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        EventViewerAsset::register($this->view);
        $this->view->registerJs("milpasos.eventViewer = {events: ".$this->eventsToJson()."};", View::POS_BEGIN);
        if ($this->onSelect)
        {
            $this->view->registerJs("milpasos.eventViewer.onSelectEvents = ".$this->onSelect.";");
        }
                
        parent::init();
    }

    /**
     * Generates an outer div with ng-app and $options, and an inner div with the ng-view and $viewDivOptions.
     * @return string
     */
    public function run()
    {
        $outerOptions = $this->options;
        if ($this->generateNgApp) {
            $outerOptions = ArrayHelper::merge([
                'ng' => [
                    'app' => static::GetAngularAppName(),
                ],
            ], $this->options);
        }

        $innerOptions = ArrayHelper::merge([
            'ng' => [
                'view' => true,
                'cloak' => true,
            ],
        ], $this->viewDivOptions);
       
        $innerDiv = Html::tag('div', '', $innerOptions);
        
        return Html::tag('div', $innerDiv, $outerOptions);
    }

    /**
     * @return string Returns the Json representation of $this->events, using Event::extendedAttributes as data for each Event.
     */
    protected function eventsToJson()
    {
        return Json::encode(array_map(function(IFormattedAttributes $e) {
            return $e->getFormattedAttributes();
        }, $this->events));
    }

    /**
     * @return string Returns the angular app name to be used when placing an ng-app to contain this widget.
     */
    public static function GetAngularAppName() {
        return EventViewerAsset::ANGULAR_APP_NAME;
    }
}
