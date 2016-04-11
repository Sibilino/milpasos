<?php

namespace app\widgets\assets;

use \yii\web\AssetBundle;

class GeoSearchAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    
    public $js = [
        'geoSearch.js',
    ];
    public $depends = [
        'app\widgets\assets\MapsAsset',
    ];
}
