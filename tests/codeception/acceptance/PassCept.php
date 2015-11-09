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

$I->wantTo('Ensure Pass CRUD works');
$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['pass/index']));

$I->mustPerformLogin();

$I->see('Create Pass');
$I->see('2 NOCHES DE HOTEL + 2 FULL PASS');
$I->see('MASTER PASS');

$I->click('a[title="View"]');
$I->expectTo('See details of first Pass');
$I->see('2 NOCHES DE HOTEL + 2 FULL PASS');
$I->see('Update');

$I->click('Update');
$I->expectTo('See an Pass Editor page');
$I->seeInField('Price', '298.00');

$I->amGoingTo("Try to enter an invalid value");
$I->fillField("Price", '');
$I->click('Update');
$I->waitForText("Price cannot be blank");

$I->fillField('Price', '69.25');
$I->click('Update');
$I->expectTo('See updated name for the Pass');
$I->waitForText('Delete'); // Wait for page change after JS validation of fields
$I->see('69.25');
$I->see('Delete');

$I->click('Delete');
$I->acceptPopup();
$I->expect('Pass to be gone');
$I->see('Create Pass');
$I->see('MASTER PASS');
$I->dontSee('2 NOCHES DE HOTEL + 2 FULL PASS');

$I->click('Create Pass');
$I->fillField('Price', '71.59');
$I->fillField('Description', 'Newest test pass');
$I->selectOption("Currency", "$");
$I->click('Create');
$I->expectTo('see created pass');
$I->waitForText('Delete'); // Wait for page change after JS validation of fields
$I->see('Newest test pass');
$I->see('$71.59');

$I->amGoingTo("Add passes to an event");
$I->amOnPage(Yii::$app->urlManager->createUrl(['event/update', 'id'=>2]));
$I->see("Add", "//*[@id='passes']//button");
$I->dontSee("Pass test via event update");
$I->fillField("Price", "222.99");
$I->fillField("Description", "Pass test via event update");
$I->click("//*[@id='passes']//button");
$I->waitForText("Showing 1-3 of 3 items.", null, "#passes .summary");
$I->dontSee("Pass test 2 via event update");
$I->fillField("Price", "42.99");
$I->fillField("Description", "Pass test 2 via event update");
$I->click("//*[@id='passes']//button");
$I->waitForText("Showing 1-4 of 4 items.", null, "#passes .summary");

$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['event/view', 'id'=>2]));
$I->expectTo("See the passes in the Event.");
$I->see("Pass test via event update");
$I->see("Pass test 2 via event update");