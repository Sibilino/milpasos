<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Registers AngularJS. Will use minimize version if YII_DEBUG is disabled.
 * @package app\assets
 */
class AngularJsAsset extends AssetBundle
{
    public $sourcePath = '@bower/angular';

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * Initializes the bundle.
     * If you override this method, make sure you call the parent implementation in the last.
     */
    public function init()
    {
        $this->js = [
            YII_DEBUG ? 'angular.js' : 'angular.min.js',
        ];
        parent::init();
    }


}
