<?php

namespace tests\codeception\fixtures;

use Codeception\TestCase;
use tests\codeception\fixtures\UserFixture;
use Codeception\Module;
use yii\test\FixtureTrait;

/**
 * This helper is used to populate the database with needed fixtures before any tests are run.
 * In this example, the database is populated with the demo login user, which is used in acceptance
 * and functional tests.  All fixtures will be loaded before the suite is started and unloaded after it
 * completes.
 */
class FixtureHelper extends Module
{

    /**
     * Redeclare visibility because codeception includes all public methods that do not start with "_"
     * and are not excluded by module settings, in actor class.
     */
    use FixtureTrait {
        loadFixtures as protected;
        fixtures as protected;
        globalFixtures as protected;
        unloadFixtures as protected;
        getFixtures as protected;
        getFixture as protected;
    }

    /**
     * @inheritdoc
     */
    public function _beforeSuite($settings = [])
    {
        $this->loadFixtures();
    }

    /**
     * Sets up the database with the given fixtures.
     * @param array $config
     * @return string
     */
    public function setUpDb(array $config = [])
    {
        $fixtures = $this->createFixtures($config);
        $this->loadFixtures($fixtures);
        return "I set up the database.";
    }

    /**
     * Unload fixtures after each test
     * @param TestCase $test
     */
    public function _after(TestCase $test)
    {
        $this->unloadFixtures();
    }

    /**
     * @inheritdoc
     */
    public function globalFixtures()
    {
        return [
            'user' => UserFixture::className(),
        ];
    }
}
