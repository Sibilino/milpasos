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
$I->wait(5); // Wait for page change after JS validation of fields
$I->see('Salsatorium', 'td');

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
$I->expectTo('see created event');
$I->wait(5); // Wait for page change after JS validation of fields
$I->see('Newest test Event', 'td');
$I->see('Badalona, Barcelona, Spain');

$I->amGoingTo("Add some links to an Event");
$I->click("Update");
$I->see("Add", "button");
$I->seeInField("Title", "");
$I->fillField("Title", "Test link 1");
$I->fillField("Url", "www.google.com");
$I->click("Add");
$I->waitForElement('input[name^=Link\[0\]');
$I->fillField('input[name=Link\[1\]\[Title\]]', "Link 2");
$I->fillField('input[name=Link\[1\]\[Url\]]', "www.yahoo.com");
$I->expect("Second link to be created even without clicking Add.");
$I->click("Update");

$I->expectTo("See the links in the Event.");
$I->wait(5); // Wait for page change after JS validation of fields
$I->see("www.google.com");
$I->see("www.yahoo.com");

