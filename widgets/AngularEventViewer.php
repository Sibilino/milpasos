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
     * Initializes ids and registers the necessary JavaScript.
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        EventViewerAsset::register($this->view);
        $this->view->registerJs("milpasos.events = ".$this->eventsToJson().";", View::POS_BEGIN);
        
        parent::init();
    }

    /**
     * Generates an outer div with ng-app and $options, and an inner div with the ng-view and $viewDivOptions.
     * @return string
     */
    public function run()
    {
        $outerOptions = ArrayHelper::merge([
            'ng' => [
                'app' => EventViewerAsset::ANGULAR_APP_NAME,
            ],
        ], $this->options);

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
}