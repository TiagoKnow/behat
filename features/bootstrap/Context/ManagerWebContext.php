<?php

namespace Context;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Testwork\Tester\Result\TestResult;
use Page\AccountCreate;
use Page\Login;

final class ManagerWebContext extends AbstractContext
{
    /**
     * @var \Page\AccountCreate
     */
    protected $accountCreatePage;

    /**
     * @var \Page\Login
     */
    protected $loginPage;

    public function __construct(AccountCreate $accountCreatePage, Login $login)
    {
        $this->accountCreatePage = $accountCreatePage;
        $this->loginPage = $login;
    }

    /**
     * @Given /^(?:eu )?(estou na) homepage$/
     * @When /^(?:eu )?(acesso a) homepage$/
     */
    public function euEstouNaHomepage()
    {
        $this->euAcessoOCaminho('');
    }

    /**
     * @Given /^(?:eu )?acesso a pagina "([^"])"$/
     */
    public function euAcessoAPagina($url)
    {
        $this->minkContext->visit($url);
    }

    /**
     * @When /^(?:eu )?aguardo (?P<tempo>\d+) segundos?$/i
     */
    public function euAguardo($tempo)
    {
        sleep($tempo);
    }

    /**
     * @Given /^(?:eu )?estou no caminho "([^"]+)"(?: vindo de "([^"]+)")?$/
     * @Given /^(?:eu )?entro no caminho "([^"]+)"(?: vindo de "([^"]+)")?$/
     * @Given /^(?:eu )?acesso o caminho "([^"]+)"(?: vindo de "([^"]+)")?$/
     */
    public function euAcessoOCaminho($caminho, $referer = null)
    {
        if ($this->minkContext->getSession()->getDriver() instanceof \Behat\Mink\Driver\GoutteDriver) {
            $this->minkContext->getSession()->getDriver()->getClient()->setServerParameter('Referer', '');
            if ($referer !== null) {
                $this->minkContext->getSession()->getDriver()->getClient()->setServerParameter('Referer', $referer);
            }
        }
        $this->minkContext->visitPath($caminho);
    }

    /**
     * @Then /^(?:eu )?devo ver um elemento "(?P<elemento>[^"]+)" com atributo "(?P<atributo>[^"]+)" com valor "(?P<valor>[^"]+)"$/i
     */
    public function devoVerUmElementoComAtributoComValor($elemento, $atributo, $valor)
    {
        $page = $this->minkContext->getSession()->getPage();
        $tags = $page->findAll('css', $elemento);

        if (!isset($tags[0])) {
            throw new \LogicException('Elemento "' . $elemento . '" nao encontrado');
        }
        $tag = current($tags);

        if (!$tag->hasAttribute($atributo)) {
            throw new \LogicException('Elemento "' . $elemento . '" nao possui o atributo "' . $atributo . '".');
        }

        if ($tag->getAttribute($atributo) != $valor) {
            throw new \LogicException('Elemento "' . $elemento . '" possui o atributo "' . $atributo . '" com valor "' . $tag->getAttribute($atributo) . '" diferente do esperado.');
        }
    }

    /**
     * @Then o elemento :arg1 deve estar com foco
     */
    public function oElementoDeveEstarComFoco($elemento)
    {
        $com_foco = $this->minkContext->getSession()->evaluateScript('return $("' . $elemento . '").is(":focus");');

        if (!$com_foco) {
            throw new \LogicException('Elemento "' . $elemento . '" nao possui foco');
        }
    }

    /**
     * @Given I hover :arg1
     */
    public function iHover($elemento)
    {
        $page = $this->getPage();
        $element = $page->find('css', $elemento);

        if (null === $element) {
            throw new \LogicException('Elemento "' . $elemento . '" nao encontrado');
        }

         $element->click();
    }

    /**
     * @When I wait :arg1 seconds
     */
    public function iWaitSeconds($arg1)
    {
        sleep($arg1);
    }

    /**
     * @Given I switch to iframe :name
     */
    public function iSwitchToIframe($name)
    {
        $driver = $this->minkContext->getSession()->getDriver();
        $driver->switchToIFrame($name);
    }

    /**
     * @When I click :elemento
     */
    public function iClick($elemento)
    {
        $page = $this->getPage();

        $element = $page->find('css', $elemento);

        if (null === $element) {
            throw new \LogicException('Elemento "' . $elemento . '" nao encontrado');
        }

        $element->click();
    }

    /**
     * @Given I set cookie name :arg1 with value false
     */
    public function iSetCookieNameWithValueFalse($arg1)
    {
        $session = $this->minkContext->getSession();
        $session->setCookie($arg1, false);
    }

    /**
     * @Given I am logged with :typeUser
     */
    public function iAmLoggedWith($typeUser)
    {
        $data = [];
        switch($typeUser) {
            case 'lojamobly' :
                $this->loginPage->setLojaMoblyPrefix();
                $data = [
                    'login' => $this->config->environments->{$this->env}->users->lojamobly->login,
                    'password' => $this->config->environments->{$this->env}->users->lojamobly->password
                ];
                break;
            case 'mobly' :
                $this->loginPage->setMoblyPrefix();
                $data = [
                    'login' => $this->config->environments->{$this->env}->users->mobly->login,
                    'password' => $this->config->environments->{$this->env}->users->mobly->password
                ];
                break;
            case  'guest' :
                return;
                break;
            default :
                throw new \InvalidArgumentException('Tipo de usuario invalido');
        }
        $this->loginPage->open();
        $this->iSetCookieNameWithValueFalse("showNewsLetterThisSession");
        $this->minkContext->reload();
        $this->loginPage
            ->setLogin($data['login'])
            ->setPassword($data['password'])
            ->send();
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

    /**
     * @When I choose the first product in catalog
     */
    public function iChooseTheFirstProductInCatalog()
    {
        $this->iSetCookieNameWithValueFalse("showNewsLetterThisSession");
        $this->minkContext->reload();
        $product = $this->getPage()->find('css','.productsCatalog .sel-catalog-product-list-item');

        if (null === $product) {
            throw new \LogicException('There is no products in catalog');
        }

        $clickable = $product->find('css', '.itm-title');
        if (null === $clickable) {
            throw new \LogicException('Products hasn\'t clickable section');
        }

        $clickable->click();
    }

    /**
     * @When /^I choose the radio button "(?P<radioButton>[^"]+)"$/i
     */
    public function iChooseRadioButton($radioButton)
    {
        $radio = $this->getPage()->find('css', $radioButton);

        if (null === $radio) {
            throw new \LogicException('Unable to find requested radio button');
        }

        $value = $radio->getAttribute('value');
        $name = $radio->getAttribute('name');
        $this->getPage()->selectFieldOption($name, $value);
    }

    /**
     * @When I fill field with mask :arg1 with :arg2
     */
    public function iFillFieldWithMaskWith($arg1, $arg2)
    {
        $element = $this->getPage()->find('css', $arg1);

        if (is_null($element)) {
            throw new \LogicException("Field doesn't exist");
        }

        $element->click();
        $element->setValue($arg2);
    }

    /**
     * @When I fill delivery address info if needed
     */
    public function iFillDeliveryAddressIfNeeded()
    {
        try {
            $this->iFillFieldWithMaskWith('#BillingAddressForm_phone', "11999571712");
            $this->iFillFieldWithMaskWith('#BillingAddressForm_postcode', "06414000");
            $this->minkContext->fillField('BillingAddressForm_street_number', "429");
        } catch (\Exception $e) {
            // don't worry about that
        }

    }

    protected function moblyPersonAccountCreate()
    {
        $this->accountCreatePage->open();
        $this->iSetCookieNameWithValueFalse("showNewsLetterThisSession");
        $this->minkContext->reload();
        $this->accountCreatePage
            ->setFirstName()
            ->setLastName()
            ->setCpf()
            ->setBirthday()
            ->setEmail()
            ->setPassword('1q2w3e')
            ->setPassword2()
            ->setGender()
            ->createAccount();
        $this->iWaitSeconds(3);
    }

    protected function moblyCompanyAccountCreate()
    {
        $this->accountCreatePage->open();
        $this->iSetCookieNameWithValueFalse("showNewsLetterThisSession");
        $this->minkContext->reload();
        $this->accountCreatePage
            ->setType('company')
            ->setLegalName()
            ->setFantasyName()
            ->setCnpj()
            ->setCompanyEmail()
            ->setState()
            ->setCustomerSegment()
            ->setPassword('1q2w3e')
            ->setPassword2()
            ->setIsento()
            ->createAccount();
        $this->iWaitSeconds(3);
    }

    protected function lojaMoblyAccountCreate()
    {
        $this->accountCreatePage->path = '/lojamobly/account/create/';
        $this->accountCreatePage->open();
        $this->iSetCookieNameWithValueFalse("showNewsLetterThisSession");
        $this->minkContext->reload();
        $this->accountCreatePage
            ->setType('company')
            ->setLegalName()
            ->setFantasyName()
            ->setCnpj()
            ->setCompanyEmail()
            ->setState()
            ->setCustomerSegment()
            ->setPassword('1q2w3e')
            ->setPassword2()
            ->setIsento()
            ->createAccount();
        $this->iWaitSeconds(3);
    }
}

