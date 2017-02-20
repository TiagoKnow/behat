<?php

namespace Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class LoginContext extends AbstractContext
{
    protected $webContext;

    /**
     * @BeforeScenario
     */
    public function getDependencies(BeforeScenarioScope $scope)
    {
        $environment =$this->getEnvironment($scope);

        $this->webContext = $environment->getContext('Context\ManagerWebContext');
    }

    /**
     * @Given I am logged with :typeUser
     */
    public function iAmLoggedWith($typeUser)
    {
        $data = [];
        switch($typeUser) {
            case 'lojamobly' :
                $data = [
                    'url' => 'http://alice.mobly.dev/lojamobly/account/login/',
                    'login' => 'dacolera360+miseravel+@gmail.com',
                    'loginFormField' => 'LoginLojaMoblyForm[email]',
                    'password' => '1q2w3e',
                    'passwordFormField' => 'LoginLojaMoblyForm[password]'
                ];
                break;
            case 'mobly' :
                $data = [
                    'url' => 'http://alice.mobly.dev/customer/account/login/',
                    'login' => 'dacolera360@gmail.com',
                    'loginFormField' => 'LoginForm[email]',
                    'password' => '1q2w3e',
                    'passwordFormField' => 'LoginForm[password]'
                ];
                break;
            case  'guest' :
                return;
                break;
            default :
                throw new \InvalidArgumentException('Tipo de usuario invalido');
        }

        $this->minkContext->visit($data['url']);
        $this->webContext->iSetCookieNameWithValueFalse("showNewsLetterThisSession");
        $this->minkContext->reload();
        $this->minkContext->fillField($data['loginFormField'], $data['login']);
        $this->minkContext->fillField($data['passwordFormField'], $data['password']);
        $this->minkContext->pressButton('Entrar');
        $this->webContext->iWaitSeconds(3);
    }
}