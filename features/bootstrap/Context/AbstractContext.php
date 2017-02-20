<?php

namespace Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Defines application features from the specific context.
 */
abstract class AbstractContext implements Context
{
    /**
     * @var \Behat\MinkExtension\Context\MinkContext
     */
    protected $minkContext;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        switch ($_SERVER['APPLICATION_ENV']) {
        case 'live':
            $baseUrl = 'http://www.mobly.com.br';
            break;
        case 'staging':
            $baseUrl = 'http://alice-staging.mobly.com.br';
            break;
        case 'dev':
            $baseUrl = 'http://alice.mobly.dev';
            break;
        default:
            throw new \RuntimeException('Variavel de ambiente APPLICATION_ENV possui valor invalido');
        }

        $environment = $scope->getEnvironment();
        $this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');
        $this->minkContext->setMinkParameter('base_url', $baseUrl);

        if ($this->minkContext->getSession()->getDriver() instanceof \Behat\Mink\Driver\Selenium2Driver) {
            $this->minkContext->getSession()->resizeWindow(1366, 768, 'current');
        }
    }

    protected function getPage()
    {
        return $this->minkContext->getSession()->getPage();
    }
}
