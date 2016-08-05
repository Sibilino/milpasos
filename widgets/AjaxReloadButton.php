<?php

namespace app\widgets;


use app\widgets\assets\AjaxReloadButtonBundle;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class AjaxReloadButton extends Widget
{
    const DATA_ATTRIBUTE = 'ajax-rld-url';
    public $tag = 'button';
    public $content = 'Delete';
    public $options = [
        'class' => 'btn btn-danger btn-xs',
    ];
    public $url;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        if (!isset($this->url)) {
            throw new InvalidConfigException("The property 'url' must be configured.");
        }
        $this->options['data-'.static::DATA_ATTRIBUTE] = $this->url;
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        AjaxReloadButtonBundle::register($this->view);
        echo Html::tag($this->tag, $this->content, $this->options);
        $this->view->registerJs($this->getScript());
    }

    protected function getScript()
    {
        $id = Json::encode($this->options['id']);
        return "milpasos.ajaxReloadBtn.register($id);";
    }
}