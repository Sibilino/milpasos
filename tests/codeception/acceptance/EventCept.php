<?php

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('Ensure Event CRUD works');
$I->amOnPage(Yii::$app->getUrlManager()->createUrl(['event/index']));
$I->see('Create Event');