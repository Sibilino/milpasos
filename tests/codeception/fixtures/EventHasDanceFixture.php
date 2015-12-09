<?php

namespace tests\codeception\fixtures;
use yii\test\ActiveFixture;

/**
 * EventHasDance fixture
 */
class EventHasDanceFixture extends ActiveFixture
{
    public $tableName = 'event_has_dance';
    public $depends = [
      'tests\codeception\fixtures\EventFixture',
      'tests\codeception\fixtures\DanceFixture',
    ];
}
