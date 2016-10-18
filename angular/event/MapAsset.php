<?php

namespace app\angular\event;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Registers Map module in AngularJS.
 * @package app\assets
 */
class MapAsset extends AssetBundle
{
    public $sourcePath = '@app/angular/event';
    public $depends = [
        'app\angular\AngularJsAsset',
    ];
    public $js = [
        'map.js',
    ];
}
