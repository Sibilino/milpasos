<?php

namespace app\widgets\assets;


use yii\web\AssetBundle;

class AjaxReloadButtonBundle extends AssetBundle
{
    public $baseUrl = '@web';
    public $js = [
        'js/ajaxReloadBtn.js',
    ];
    public $depends = [
        'app\assets\MilpasosAsset',
    ];
}