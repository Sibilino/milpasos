<?php

namespace app\widgets\assets;

use yii\web\AssetBundle;

/**
 * Assets for the EventViewer widget.
 * @package app\widgets\assets
 */
class EventViewerAsset extends AssetBundle
{
    /**
     * The name given to the AngularJs app in the JavaScript file.
     */
    const ANGULAR_APP_NAME = 'EventViewerApp';
    /**
     * he name given to the AngularJs controller in the JavaScript file.
     */
    const ANGULAR_CONTROLLER_NAME = 'EventViewer';

    public $baseUrl = '@web/js';
    public $depends = [
        'app\assets\AngularJsAsset',
        'app\assets\MilpasosAsset',
    ];
    public $js = [
        'eventViewer.js',
    ];
}
