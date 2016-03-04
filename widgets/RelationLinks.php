<?php

namespace app\widgets;

use ReflectionClass;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * This widget generates an unordered list of links to each of the records existing for a model's relation.
 * @package app\widgets
 */
class RelationLinks extends Widget
{
    /**
     * @var Model The model for which to find the related objects.
     */
    public $model;
    /**
     * @var string The name of the relation.
     */
    public $relation;
    /**
     * @var string Optional. The name of the controller to which the generated links will point.
     */
    public $controller = '';
    /**
     * @var string The attribute of the relation that will be used as text for its generated link.
     */
    public $labelAttr = 'name';

    /**
     * @var ActiveQuery The Query object of the relation.
     */
    private $_relationQuery;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
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

    /**
     * @inheritdoc
     * @return string
     */
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
