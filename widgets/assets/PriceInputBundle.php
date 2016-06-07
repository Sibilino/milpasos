<?php

namespace app\widgets\assets;


use yii\web\AssetBundle;

/**
 * Defines the files to be registered in the view in order to use the PriceInput widget.
 * @package app\widgets\assets
 */
class PriceInputBundle extends AssetBundle
{
    public $baseUrl = '@web';
    public $css = [
        'css/PriceInput.css',
    ];
}