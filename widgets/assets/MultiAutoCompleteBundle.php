<?php

namespace app\widgets\assets;


use yii\web\AssetBundle;

class MultiAutoCompleteBundle extends AssetBundle
{
    public $baseUrl = '@web';
    public $js = [
        'js/multiAutoComplete.js',
    ];
    public $depends = [
        'app\assets\MilpasosAsset',
    ];
}