<?php

namespace app\widgets;

use yii\widgets\ListView;

class ListForm extends ListView
{
    public $formView;
    public $openParam = 'open-model';
    
    private $_openModelKey;
    
    public function init() {
        parent::init();
        $this->_openModelKey = Yii::$app->request->getQueryParam($this->openParam);
    }
    
    public function run() {
        Pjax::begin();
        parent::run();
        Pjax::end();
    }
    
    public function renderItems() {
        return parent::renderItems().$this->renderForm();
    }
    
    public function renderForm() {
        return ''; //@TODO
    }
}
