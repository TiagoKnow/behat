<?php

namespace Context;

use Page\AccountCreate;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class CreateContext extends AbstractContext
{
    protected $accountCreatePage;

    protected $webContext;

    /**
     * @BeforeScenario
     */
    public function getDependencies(BeforeScenarioScope $scope)
    {
        $environment =$this->getEnvironment($scope);

        $this->webContext = $environment->getContext('Context\ManagerWebContext');
    }

    public function __construct(AccountCreate $accountCreatePage)
    {
        $this->accountCreatePage = $accountCreatePage;
    }

    /**
     * @Given I am signup :site as :typeUser
     */
    public function iAmSignupAs($site, $typeUser)
    {
        switch ($site) {
            case 'lojamobly' :
                if ($typeUser == 'PF') {
                    throw new \LogicException('Loja mobly esta liberada apenas para empresas');
                }
                $this->lojamoblyAccountCreate();
                break;
            case 'mobly' :
                if ($typeUser == 'PF') {
                    $this->moblyPersonAccountCreate();
                } else {
                    $this->moblyCompanyAccountCreate();
                }
                break;
            default :
                throw new \InvalidArgumentException('Site Invalido');
        }
    }

    protected function moblyPersonAccountCreate()
    {
        $this->accountCreatePage->open();
        $this->webContext->iSetCookieNameWithValueFalse("showNewsLetterThisSession");
        $this->minkContext->reload();
        $this->accountCreatePage
            ->setFirstName()
            ->setLastName()
            ->setCpf()
            ->setBirthday()
            ->setEmail()
            ->setPassword()
            ->setPassword2()
            ->setGender()
            ->createAccount();
        $this->webContext->iWaitSeconds(3);
    }

    protected function moblyCompanyAccountCreate()
    {
        $this->accountCreatePage->open();
        $this->webContext->iSetCookieNameWithValueFalse("showNewsLetterThisSession");
        $this->minkContext->reload();
        $this->accountCreatePage
            ->setType('company')
            ->setLegalName()
            ->setFantasyName()
            ->setCnpj()
            ->setCompanyEmail()
            ->setState()
            ->setCustomerSegment()
            ->setPassword()
            ->setPassword2()
            ->setIsento()
            ->createAccount();
        $this->webContext->iWaitSeconds(3);
    }

    protected function lojaMoblyAccountCreate()
    {
        $this->accountCreatePage->path = 'http://alice.mobly.dev/lojamobly/account/create/';
        $this->accountCreatePage->open();
        $this->webContext->iSetCookieNameWithValueFalse("showNewsLetterThisSession");
        $this->minkContext->reload();
        $this->accountCreatePage
            ->setType('company')
            ->setLegalName()
            ->setFantasyName()
            ->setCnpj()
            ->setCompanyEmail()
            ->setState()
            ->setCustomerSegment()
            ->setPassword()
            ->setPassword2()
            ->setIsento()
            ->createAccount();
        $this->webContext->iWaitSeconds(3);
    }
}