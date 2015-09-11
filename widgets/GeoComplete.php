<?php

namespace app\widgets;

class GeoComplete extends \yii\jui\AutoComplete
{

    public function init()
    {
        MapsAsset::register($this->view);
        parent::init();
    }
}