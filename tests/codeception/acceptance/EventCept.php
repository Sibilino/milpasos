<?php

/* @var $scenario Codeception\Scenario */

use AcceptanceTester\RegisteredUserSteps as RegisteredTester;
use tests\codeception\fixtures\EventFixture;
use tests\codeception\fixtures\EventHasDanceFixture;
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
    'event_has_dance' => [
        'class' => EventHasDanceFixture::className(),
    ],
    'pass' => [
        'class' => PassFixture::className(),
    ],
]);

$I->wantTo('Ensure Event CRUD works');
$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['event/index']));

$I->mustPerformLogin();

$I->see('Create Event');
$I->see('Salsea');
$I->see('Bachatea');

$I->click('a[title="View"]');
$I->expectTo('See details of first Event');
$I->see('Salsea');
$I->see('2015-11-06');
$I->see('Update');

$I->click('Update');
$I->expectTo('See an Event Editor page');
$I->seeInField('Name', 'Salsea');
$I->seeCheckboxIsChecked('Kizomba');
$I->seeCheckboxIsChecked('Salsa');
$I->dontSeeCheckboxIsChecked('Bachata');

$I->amGoingTo("Try to enter an invalid value");
$I->fillField("Name", '');
$I->click('Update');
$I->waitForText("Name cannot be blank");

$I->fillField('Name', 'Salsatorium');
$I->checkOption("Bachata");
$I->uncheckOption("Kizomba");
$I->click('Update');
$I->expectTo('See updated fields for the Event');
$I->wait(5); // Wait for page change after JS validation of fields
$I->see('Salsatorium', 'td');
$I->see("Bachata");
$I->dontSee("Kizomba");

$I->click('Delete');
$I->acceptPopup();
$I->expect('Event to be gone');
$I->see('Create Event');
$I->see('Bachatea');
$I->dontSee('Salsea');
$I->dontSee('Salsatorium');
$I->dontSee('Newest test Event');

$I->click('Create Event');
$I->fillField('Name', 'Newest test Event');
$I->fillField('Start Date', '2015-12-01');
$I->fillField('End Date', '2015-12-05');

$I->amGoingTo("Type an incomplete address");
$I->fillField('Address', 'Badalona');
$I->waitForText("Spain"); // Wait for suggestions from Google, but dont click on any
$I->click("Create");
$I->expectTo("see validation errors");
$I->waitForText("Please select an address from the list of suggestions.");
$I->dontSee("Delete", ['css' => 'a.btn']);
$I->seeInField("Address", "");

$I->amGoingTo("Choose a valid address");
$I->fillField('Address', 'Badalona');
$I->waitForText("Spain");
$I->click(".ui-menu-item:nth-of-type(1)"); // Click on first suggestion
$I->click('Create');
$I->expectTo('see created event and link creator');
$I->waitForText("Links", null, "h2");
$I->see("Passes", 'h2');
$I->see("Add","button");
$I->seeInField('Name','Newest test Event');
$I->seeInField("Address", 'Badalona, Barcelona, Spain');
