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