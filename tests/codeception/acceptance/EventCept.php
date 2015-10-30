<?php

/* @var $scenario Codeception\Scenario */

use AcceptanceTester\RegisteredUserSteps as RegisteredTester;

$I = new RegisteredTester($scenario);
$I->wantTo('Ensure Event CRUD works');
$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['event/index']));

$I->mustPerformLogin();

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

$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['event/view', 'id'=>3]));
$I->expectTo("See the links in the Event.");
$I->see("www.google.com");
$I->see("www.yahoo.com");

$I->amGoingTo("Add passes to an event");
$I->amOnPage(Yii::$app->urlManager->createUrl(['event/update', 'id'=>2]));
$I->see("Add", "//*[@id='passes']//button");
$I->dontSee("Pass test via event update");
$I->fillField("Price", "222.99");
$I->fillField("Description", "Pass test via event update");
$I->click("//*[@id='passes']//button");
$I->waitForText("Showing 1-1 of 1 item.", null, "#passes .summary");
$I->dontSee("Pass test 2 via event update");
$I->fillField("Price", "42.99");
$I->fillField("Description", "Pass test 2 via event update");
$I->click("//*[@id='passes']//button");
$I->waitForText("Showing 1-2 of 2 items.", null, "#passes .summary");

$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['event/view', 'id'=>2]));
$I->expectTo("See the passes in the Event.");
$I->see("Pass test via event update");
$I->see("Pass test 2 via event update");