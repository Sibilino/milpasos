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
    /**
     * @var string GMaps API key, required.
     */
    public $key;
    /**
     * @var string Language to request the library in. Optional.
     */
    public $language;

    public $jsOptions = [
        'async' => true,
        'defer' => true,
    ];
    public $depends = [
        'app\widgets\assets\GMapsModule',
    ];

    public function init()
    {
        if (!$this->key)
            throw new InvalidConfigException;

        $url = "https://maps.googleapis.com/maps/api/js?key=$this->key&libraries=places&callback=milpasos.gmaps.ready";
        if ($this->language) {
            $url .= "&language=$this->language";
        }
        $this->js []= $url;
        parent::init();
    }
}
