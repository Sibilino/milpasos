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

    /**
     * Initializes the bundle.
     * If you override this method, make sure you call the parent implementation in the last.
     */
    public function init()
    {
        $this->js []= Url::to('@web/js/milpasos.js');
        parent::init();
    }
}
