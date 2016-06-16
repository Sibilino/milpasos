<?php

namespace app\widgets\assets;

use \yii\web\AssetBundle;

/**
 * Registers the JavaScript wrapper module for GMaps that widgets can use to easily manage maps.
 * @package app\widgets\assets
 */
class GMapsModule extends AssetBundle
{
    public $baseUrl = '@web';
    public $js = [
        'js/gmaps.js',
    ];
    public $depends = [
        'app\assets\MilpasosAsset',
        'app\assets\GMapsLibrary',
    ];
}
