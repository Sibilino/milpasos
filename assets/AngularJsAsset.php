<?php

namespace app\assets;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Registers AngularJS. Will use minimize version if YII_DEBUG is disabled.
 * @package app\assets
 */
class AngularJsAsset extends AssetBundle
{
    const ANGULAR_APP_NAME = 'AngularMilpasos';

    public $sourcePath = '@bower';

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
            YII_DEBUG ? 'angular/angular.js' : 'angular/angular.min.js',
            YII_DEBUG ? 'angular-route/angular-route.js' : 'angular-route/angular-route.min.js',
            Url::to('@web/js/angular-app.js'),
        ];
        parent::init();
    }
}
