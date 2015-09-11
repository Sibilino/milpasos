<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use \yii\web\AssetBundle;
use \yii\web\View;

/**
 * Class MapsAsset registers the Google Maps API library.
 * This bundle requires the $key property, configured by the Asset Manager.
 * For example in the app's components config:
 * 'assetManager' => [
 *       'bundles' => [
 *           'app\widgets\MapsAsset' => [
 *                  'key' => 'your_key',
 *               ],
 *           ]
 *       ],
 * @package app\widgets
 */
class MapsAsset extends AssetBundle
{
    public $key;

    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];

    public function init()
    {
        if (!$this->key)
            throw new InvalidConfigException;

        $this->js []= "https://maps.googleapis.com/maps/api/js?key=$this->key";
        parent::init();
    }
}