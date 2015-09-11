<?php

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('Ensure Event CRUD works');
$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['event/index']));
$I->see('Create Event');
$I->see('Salsea');
$I->see('Bachatea');

$I->click('a[title="View"]');
$I->expectTo('See details of first Event');
$I->see('Salsea');
$I->see('Update');

$I->click('Update');
$I->expectTo('See an Event Editor page');
$I->seeInField('Name', 'Salsea');

$I->amGoingTo("Try to enter an invalid value");
$I->fillField("Name", '');
$I->click('Update');
$I->waitForText("Name cannot be blank");

$I->fillField('Name', 'Salsatorium');
$I->click('Update');
$I->expectTo('See updated name for the Event');
$I->waitForText('Delete'); // Wait for page change after JS validation of fields
$I->see('Salsatorium');
$I->see('Delete');

$I->click('Delete');
$I->acceptPopup();
$I->expect('Event to be gone');
$I->see('Create Event');
$I->see('Bachatea');
$I->dontSee('Salsea');

$I->click('Create Event');
$I->fillField('Name', 'Newest test Event');
$I->fillField('Start Date', '2015-12-01');
$I->fillField('End Date', '2015-12-05');
$I->fillField('Address', 'Badalona');
$I->waitForText("Spain"); // Wait for suggestions from Google
$I->click(".suggest:nth-of-type(1)"); // Click on first suggestion
$I->waitForText('Badalona, Spain', 30, '#event-address');
$I->click('Create');
$I->expectTo('see created event');
$I->waitForText('Delete'); // Wait for page change after JS validation of fields
$I->see('Newest test Event');
$I->see('Badalona, Spain');