<?php
namespace AcceptanceTester;

class RegisteredUserSteps extends \AcceptanceTester
{
    /**
     * Ensures that the user is in the login page, and logs the user in.
     */
    public function mustPerformLogin($username = 'erau', $password = 'password_0')
    {
        $I = $this;
        $I->expectTo("be redirected to login page");
        $I->see("Login", 'h1');
        $I->dontSee("Logout");

        $I->amGoingTo("log in");
        $I->fillField("LoginForm[username]", $username);
        $I->fillField("LoginForm[password]", $password);
        $I->click("login-button");
        if (method_exists($I, 'wait')) {
            $I->wait(3); // only for selenium
        }
        $I->see('Logout (erau)');
    }
}