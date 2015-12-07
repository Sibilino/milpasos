<?php

namespace app\components;

use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\helpers\ArrayHelper;
use yii\validators\EachValidator;

/**
 * Class ManyToManyBehavior
 * @property ActiveRecord $owner
 * @package app\components
 */
class ManyToManyBehavior extends Behavior
{
    /**
     * @var string
     */
    public $relation;
    /**
     * @var string
     */
    public $idListAttr;

    /**
     * @return array
     */
    public function events() {
        return [
            Model::EVENT_AFTER_VALIDATE => 'validateIdList',
            ActiveRecord::EVENT_AFTER_FIND => 'loadIdList',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveRelation',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveRelation',
        ];
    }

    /**
     * @param Event $event
     */
    public function validateIdList(Event $event) {
        $relationClass = $this->owner->getRelation($this->relation)->modelClass;
        // TODO: Replace each + exist validators for one findAll()
        $validator = new EachValidator([
            'rule' => ['exist', 'targetClass' => $relationClass, 'targetAttribute'=>'id'],
        ]);
        $validator->validateAttribute($this->owner, $this->idListAttr);
    }

    /**
     * @param Event $event
     */
    public function loadIdList(Event $event) {
        $model = $this->owner;
        $model->{$this->idListAttr} = ArrayHelper::getColumn($model->{$this->relation}, 'id');
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function saveRelation(AfterSaveEvent $event) {
        $model = $this->owner;
        $model->unlinkAll($this->relation, true); // Delete all existing links
        $ids = $model->{$this->idListAttr};
        $relatedModels = $model->getRelation($this->relation)->andWhere(['id' => $ids])->all();
        
        foreach ($relatedModels as $record) {
            $model->link($this->relation, $record);
        }
    }
}
