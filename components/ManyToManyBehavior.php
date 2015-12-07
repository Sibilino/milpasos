<?php

namespace app\components;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;

class ManyToManyBehavior extends Behavior
{
    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'loadIdList',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveRelation',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveRelation',
        ];
    }
    public function loadIdList(Event $event) {
        // TODO
    }
    public function saveRelation(AfterSaveEvent $event) {
        // TODO
    }
}
