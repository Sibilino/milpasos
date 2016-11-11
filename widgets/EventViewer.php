<?php

namespace app\widgets;

use app\widgets\assets\EventViewerAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Generates an AngularJs application that controls a list of Events.
 * The AngularJs controller's scope is accessible as a var with the name configured in $controllerVar.
 * The view to be render inside the controller is configured in $viewName.
 * After running the widget, the "milpasos.EventViewer" JavaScript object is externally available to interact with the
 * AngularJs controller.
 *
 * @package app\widgets
 */
class EventViewer extends Widget
{
    /**
     * @var array HTML options for the container DIV, which contains the AngularJs app.
     */
    public $options = [];
    /**
     * @var array HTML options for the inner DIV, which contains the AngularJs controller.
     */
    public $controllerDivOptions = [];
    /**
     * @var string Name of the JavaScript var that holds the Angular controller's scope, for use in the view.
     */
    public $controllerVar = 'Viewer';
    /**
     * @var string If given, this string will be used as ng-init in the Angular controller DIV.
     */
    public $controllerInit;
    /**
     * @var string Name of the view file that this widget will render.
     */
    public $viewName = 'eventViewerList';

    /**
     * Initializes ids and registers the necessary JavaScript.
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        if (!isset($this->controllerDivOptions['id'])) {
            $this->controllerDivOptions['id'] = $this->options['id'].'-ev-controller';
        }

        EventViewerAsset::register($this->view);
        $this->publishAngularElement();

        parent::init();
    }

    /**
     * Executes the widget.
     * @return string An outer DIV with ng-app, and an inner DIV with the ng-controller and the viewfile as content.
     */
    public function run()
    {
        $innerOptions = ArrayHelper::merge([
            'ng' => [
                'controller' => EventViewerAsset::ANGULAR_CONTROLLER_NAME." as $this->controllerVar",
            ],
        ], $this->controllerDivOptions);
        if (isset($this->controllerInit)) {
            $innerOptions['ng']['init'] = $this->controllerInit;
        }
        $innerDiv = Html::tag('div', $this->render($this->viewName, [
            'controllerVar' => $this->controllerVar,
        ]), $innerOptions);
        return Html::tag('div', $innerDiv, ArrayHelper::merge([
            'ng' => [
                'app' => EventViewerAsset::ANGULAR_APP_NAME,
            ],
        ], $this->options));
    }

    /**
     * Exposes the AngularJs element into the external "milpasos.EventViewer" variable, so that external JavaScript
     * can access it. The variable contains an object with the following properties:
     * <ul>
     * <li>element: the AngularJs element containing the controller. Has .scope(), .controller(), etc.</li>
     * <li>call: function (funcName, arg), calls a controller's function with funcName and passes the given arg.</li>
     * </ul>
     * Example: milpasos.EventViewer.call('selectEvents', [27,14]);
     */
    protected function publishAngularElement()
    {
        $this->view->registerJs('
            // Publish angular controller for external use
            (function ($) {
            
                // Angular element, contains .scope(), .controller(), etc.
                milpasos.EventViewer = {
                    element: angular.element(document.getElementById("' . $this->controllerDivOptions['id'] . '"))
                };
                
                // Allows calling any function in angular controller, applying the results to its scope
                var ew = milpasos.EventViewer;
                ew.call = function (funcName, arg) {
                    if ($.isFunction(ew.element.controller()[funcName])) {
                        ew.element.scope().$apply(function () {
                            ew.element.controller()[funcName](arg);
                        });
                    }
                }
            })(jQuery);
        ');
    }

}