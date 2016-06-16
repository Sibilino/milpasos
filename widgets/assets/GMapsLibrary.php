<?php

namespace app\widgets\assets;

use yii\base\InvalidConfigException;
use \yii\web\AssetBundle;

/**
 * Class GMapsLibrary registers the Google Maps API library.
 * This bundle requires the $key property, configured by the Asset Manager.
 * For example in the app's components config:
 * 'assetManager' => [
 *       'bundles' => [
 *           'app\widgets\assets\GMapsLibrary' => [
 *                  'key' => 'your_key',
 *               ],
 *           ]
 *       ],
 * @package app\widgets\assets
 */
class GMapsLibrary extends AssetBundle
{
    public $key;

    public $jsOptions = [
        'async' => true,
        'defer' => true,
    ];
    public $depends = [
        'app\assets\MilpasosAsset',
    ];

    public function init()
    {
        if (!$this->key)
            throw new InvalidConfigException;

        $this->js []= "https://maps.googleapis.com/maps/api/js?key=$this->key&libraries=places&callback=milpasos.gmaps.ready";
        parent::init();
    }
}
