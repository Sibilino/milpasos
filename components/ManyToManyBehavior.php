<?php

namespace app\components;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;

class ManyToManyBehavior extends Behavior
{
    public $relation;
    
    public $idListAttr;
    
    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'loadIdList',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveRelation',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveRelation',
        ];
    }
    
    public function loadIdList(Event $event) {
        $model = $this->owner;
        $model->${$this->idListAttr} = ArrayHelper::getColumn($model->${$this->relation}, 'id');
    }
    
    public function saveRelation(AfterSaveEvent $event) {
        $model = $this->owner;
        $relationFinder = $model->getRelation($this->relation);
        
        $model->unlinkAll($this->relation, true); // Delete all existing links
        foreach ($model->${$this->idListAttr} as $id) {
            $relatedRecord = $relationFinder->where(['id' => $id])->one();
            if ($relatedRecord !== null) {
                $this->link($this->relation, $relatedRecord);
            }
        }
    }
}
