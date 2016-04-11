<?php

namespace app\assets;

use yii\web\AssetBundle;

class MilpasosAsset extends AssetBundle
{
    public $js = [
        '@web/js/milpasos.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ],
}
