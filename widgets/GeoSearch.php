<?php

namespace app\widgets;

use yii\widgets\InputWidget;

class GeoSearch extends InputWidget
{
    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        
        parent::init();
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        MapsAsset::register($this->view);
    }
}
