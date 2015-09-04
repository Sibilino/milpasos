<?php

namespace tests\codeception\fixtures;

use yii\test\ActiveFixture;

/**
 * Pass fixture
 */
class PassFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Pass';
    public $depends = ['tests\codeception\fixtures\EventFixture'];
}
