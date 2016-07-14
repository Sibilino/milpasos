<?php

namespace app\widgets;

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

class ListForm extends ListView
{
    public $formView;
    public $formViewParams = [];
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
    
    public function renderItem($model, $key, $index) {
        if ($key == $this->_openModelKey) {
            return $this->renderForm($model, $key, $index);
        }
        return parent::renderItem($model, $key, $index);
    }
    
    public function renderForm($model, $key, $index) {
        $openUrl = Url::current([
            $this->openParam => $key,
        ]);
        if (is_string($this->formView)) {
            return $this->getView()->render($this->formView, array_merge([
                'model' => $model,
                'key' => $key,
                'index' => $index,
                'widget' => $this,
                'openUrl' => $openUrl,
            ], $this->formViewParams));
        }
        return call_user_func($this->formView, $model, $key, $index, $this, $openUrl);
    }
}
