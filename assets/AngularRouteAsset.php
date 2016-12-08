<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Registers AngularJS. Will use minimize version if YII_DEBUG is disabled.
 * @package app\assets
 */
class AngularRouteAsset extends AssetBundle
{
    public $sourcePath = '@bower/angular-route';

    public $depends = [
        'app\assets\AngularJsAsset',
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];

    /**
     * Initializes the bundle.
     * If you override this method, make sure you call the parent implementation in the last.
     */
    public function init()
    {
        $this->js = [
            YII_DEBUG ? 'angular-route.js' : 'angular-route.min.js',
        ];
        parent::init();
    }
}
