<?php

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);

$I->wantTo('Ensure Pass CRUD works');
$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['pass/index']));
$I->see('Create Pass');
$I->see('BEGINNER PASS');
$I->see('MASTER PASS');

$I->click('a[title="View"]');
$I->expectTo('See details of first Pass');
$I->see('BEGINNER PASS');
$I->see('Update');

$I->click('Update');
$I->expectTo('See an Pass Editor page');
$I->seeInField('Price', '119.50');

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
$I->dontSee('BEGINNER PASS');

$I->click('Create Pass');
$I->fillField('Price', '71.59');
$I->fillField('Description', 'Newest test pass');
$I->click('Create');
$I->expectTo('see created pass');
$I->waitForText('Delete'); // Wait for page change after JS validation of fields
$I->see('Newest test pass');
$I->see('71.59');