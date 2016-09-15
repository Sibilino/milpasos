<?php

namespace app\widgets\assets;


use yii\web\AssetBundle;

/**
 * To register the necessary JavaScript code for the MultiAutoComplete widget.
 * @package app\widgets\assets
 */
class MultiAutoCompleteBundle extends AssetBundle
{
    public $baseUrl = '@web';
    public $js = [
        'js/multiAutoComplete.js',
    ];
    public $css = [
        'css/MultiAutoComplete.css',
    ];
    public $depends = [
        'app\assets\MilpasosAsset',
    ];
}