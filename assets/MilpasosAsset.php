<?php

namespace app\assets;

use yii\helpers\Url;
use yii\web\AssetBundle;

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
    public $js = [
        'js/milpasos.js',
    ];
}
