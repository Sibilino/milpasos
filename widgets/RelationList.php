<?php

namespace app\widgets;

use ReflectionClass;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

class RelationList extends Widget
{
    public $model;
    public $relation;
    public $controller;
    public $labelAttr = 'name';
    
    private $_relationQuery;
    
    public function init()
    {
        parent::init();
        if (!$this->model instanceof ActiveRecord) {
            throw new InvalidConfigException('The "model" property must be an instance of yii\db\ActiveRecord.');
        }
        $this->_relationQuery = $this->model->getRelation($this->relation); // Throws exception if no relation found
        if (empty($this->controller)) {
            $reflection = new ReflectionClass($this->_relationQuery->modelClass);
            $this->controller = Inflector::camel2id($reflection->getShortName());
        }
    }
    
    public function run()
    {
        $widget = $this;
        $models = $this->_relationQuery->all();
        $links = array_map(function (ActiveRecord $m) use ($widget) {
            return Html::a(ucfirst($m->{$widget->labelAttr}), Url::to(["/$widget->controller/view/$m->id"]));
        }, $models);
        return Html::ul($links, ['encode'=>false]);
    }
}
