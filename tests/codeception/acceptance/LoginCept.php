<?php

use tests\codeception\_pages\LoginPage;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that login works');

$loginPage = LoginPage::openBy($I);

$I->see('Login', 'h1');

$I->amGoingTo('try to login with empty credentials');
$loginPage->login('', '');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see validations errors');
$I->see('Username cannot be blank.');
$I->see('Password cannot be blank.');

$I->amGoingTo('try to login with wrong credentials');
$loginPage->login('erau', 'wrong');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see validations errors');
$I->see('Incorrect username or password.');

$I->amGoingTo('try to login with correct credentials');
$loginPage->login('erau', 'password_0');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see user info');
$I->see('Logout (erau)');
