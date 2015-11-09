<?php

/* @var $scenario Codeception\Scenario */

use AcceptanceTester\RegisteredUserSteps as RegisteredTester;
use tests\codeception\fixtures\EventFixture;
use tests\codeception\fixtures\PassFixture;
use tests\codeception\fixtures\UserFixture;

$I = new RegisteredTester($scenario);

$I->setUpDb([
    'user' => [
        'class' => UserFixture::className(),
    ],
    'event' => [
        'class' => EventFixture::className(),
    ],
    'pass' => [
        'class' => PassFixture::className(),
    ],
]);

$I->wantTo('Ensure Link GUIs work');
$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['event/update', 'id'=>2]));

$I->mustPerformLogin();

$I->amGoingTo("Add some links to an Event");
$I->see("Links", 'h2');
$I->see("Add", "//*[@id='links']//button");
$I->seeInField("Title", "");
$I->fillField("Title", "Test link 1");
$I->fillField("Url", "www.google.com");
$I->click("//*[@id='links']//button");
$I->waitForText("Showing 1-1 of 1 item.", null, "#links .summary");
$I->fillField('Title', "Link 2");
$I->fillField('Url', "www.yahoo.com");
$I->expect("Second link to be added.");
$I->click("//*[@id='links']//button");
$I->waitForText("Showing 1-2 of 2 items.", null, "#links .summary");
$I->see("www.google.com");
$I->see("www.yahoo.com");

$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['event/view', 'id'=>2]));
$I->expectTo("See the links in the Event.");
$I->see("www.google.com");
$I->see("www.yahoo.com");