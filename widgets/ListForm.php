<?php

namespace app\widgets;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ListView;

/**
 * This widget displays a ListView where one of the items can be opened to display a form.
 * In addition to the normal $itemView, a $formView must be specified.
 * The items can be opened and closed by having the view generate a link to the urls provided by the
 * getOpenUrl or getCloseUrl methods.
 * @package app\widgets
 */
class ListForm extends ListView
{
    /**
     * @var string The view to be used for open items (usually a form).
     */
    public $formView;
    /**
     * @var array Extra parameters to be passed to the render function when rendering the form view.
     */
    public $formViewParams = [];
    /**
     * The GET parameter that will hold the list element that is currently open. Optional, default is this widget's id.
     * @var string
     */
    public $openParam;

    /**
     * @var mixed The key of the model that was specified as open in the current GET request parameters.
     */
    private $_openModelKey;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        parent::init();
        if (!isset($this->openParam)) {
            $this->openParam = $this->getId();
        }
        $this->_openModelKey = Yii::$app->request->getQueryParam($this->openParam);
    }

    /**
     * Renders the itemView for a given model, or the formView if the given model is currently open.
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     * @return mixed|string
     */
    public function renderItem($model, $key, $index) {
        if ($key == $this->_openModelKey) {
            return $this->renderForm($model, $key, $index);
        }
        return parent::renderItem($model, $key, $index);
    }

    /**
     * Renders the formView for the given model.
     * @param $model
     * @param $key
     * @param $index
     * @return mixed|string
     */
    public function renderForm($model, $key, $index) {
        if (is_string($this->formView)) {
            return $this->getView()->render($this->formView, array_merge([
                'model' => $model,
                'key' => $key,
                'index' => $index,
                'widget' => $this,
            ], $this->formViewParams));
        }
        return call_user_func($this->formView, $model, $key, $index, $this);
    }

    /**
     * Returns the Url for a link that opens the model identified by $key.
     * @param $key
     * @return string
     */
    public function getOpenUrl($key) {
        return Url::current([
            $this->openParam => $key,
        ]);
    }

    /**
     * Returns the Url for a link that closes any open models.
     * @return string
     */
    public function getCloseUrl() {
        return Url::current([
            $this->openParam => null,
        ]);
    }

    /**
     * @return bool Whether this widget currently has an open model.
     */
    public function hasOpenModel() {
        return $this->_openModelKey !== null;
    }

    /**
     * Returns the current open model.
     * @return mixed
     * @throws Exception If there is no open model.
     */
    public function getOpenModel() {
        $models = $this->dataProvider->getModels();
        $keys = $this->dataProvider->getKeys();
        $models = array_combine($keys, $models);
        if (!$this->hasOpenModel() || !isset($models[$this->_openModelKey])) {
            throw new Exception("Cannot retrieve open model: no open model found.");
        }
        return $models[$this->_openModelKey];
    }
}
