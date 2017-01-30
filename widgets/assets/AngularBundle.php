<?php

namespace app\widgets\assets;

use app\assets\AngularJsAsset;
use yii\web\AssetBundle;

abstract class AngularBundle extends AssetBundle
{
    public $baseUrl = '@web/js';
    public $depends = [
        'app\assets\AngularJsAsset',
    ];

    public static function GetAngularAppName() {
        return AngularJsAsset::ANGULAR_APP_NAME;
    }
}