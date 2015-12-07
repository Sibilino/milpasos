<?php

namespace app\components;

use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\validators\EachValidator;

class ManyToManyBehavior extends Behavior
{
    public $relation;
    public $idListAttr;
    
    public function events() {
        return [
            Model::EVENT_AFTER_VALIDATE => 'validateIdList',
            ActiveRecord::EVENT_AFTER_FIND => 'loadIdList',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveRelation',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveRelation',
        ];
    }
    
    public function validateIdList(Event $event) {
        $relationClass = $this->owner->getRelation($this->relation)->modelClass;
        $validator = new EachValidator([
            'rule' => ['exist', 'targetClass' => $relationClass, 'targetAttribute'=>'id'],
        ]);
        $validator->validateAttribute($this->owner, $this->idListAttr);
    }
    
    public function loadIdList(Event $event) {
        $model = $this->owner;
        $model->${$this->idListAttr} = ArrayHelper::getColumn($model->${$this->relation}, 'id');
    }
    
    public function saveRelation(AfterSaveEvent $event) {
        $model = $this->owner;
        
        $model->unlinkAll($this->relation, true); // Delete all existing links
        
        $relationFinder = $model->getRelation($this->relation);
        
        foreach ($relationFinder->where(['id' => $ids])->all() as $record) {
            $this->link($this->relation, $record);
        }
    }
}
