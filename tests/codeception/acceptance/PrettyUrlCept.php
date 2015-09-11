<?php

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that URLs for routes are shortened');
$I->amOnPage(Yii::$app->urlManager->createUrl('site/index'));
$I->dontSeeInCurrentUrl('?r=');