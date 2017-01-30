<?php

namespace app\widgets\assets;

use app\assets\AngularJsAsset;
use yii\web\AssetBundle;

/**
 * Assets for the EventViewer widget.
 * @package app\widgets\assets
 */
class EventViewerBundle extends AssetBundle
{
    public $baseUrl = '@web/js';
    public $depends = [
        'app\assets\AngularJsAsset',
        'app\assets\MilpasosAsset',
    ];
    public $js = [
        'eventViewer.js',
    ];
    
    public static function GetAngularAppName() {
        return AngularJsAsset::ANGULAR_APP_NAME;
    }
}