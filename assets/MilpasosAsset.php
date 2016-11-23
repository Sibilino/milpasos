<?php

namespace app\assets;

use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Registers the milpasos js module.
 * @package app\assets
 */
class MilpasosAsset extends AssetBundle
{
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $baseUrl = '@web';
    public $jsOptions = ['position' => View::POS_HEAD];
    public $js = [
        'js/milpasos.js',
    ];
}
