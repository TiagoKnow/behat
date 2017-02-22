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

    protected $baseUrl;

    protected $secureBaseUrl;

    public static $sharedUrl;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $this->getEnvironment($scope);
        $this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');
        $this->minkContext->setMinkParameter('base_url', $this->baseUrl);

        static::$sharedUrl = $this->minkContext->getMinkParameter('base_url');

        if ($this->minkContext->getSession()->getDriver() instanceof \Behat\Mink\Driver\Selenium2Driver) {
            $this->minkContext->getSession()->resizeWindow(1920, 1080, 'current');
        }
    }

    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    protected function getPage()
    {
        return $this->minkContext->getSession()->getPage();
    }

    /**
     * @param BeforeScenarioScope $scope
     * @return \Behat\Testwork\Environment\Environment
     */
    protected function getEnvironment(BeforeScenarioScope $scope)
    {
        switch ($_SERVER['APPLICATION_ENV']) {
            case 'live':
                $this->baseUrl = 'http://www.mobly.com.br';
                $this->secureBaseUrl = 'https://secure.mobly.com.br';
                break;
            case 'staging':
                $this->baseUrl = 'http://rocket:rock4me@alice-staging01.mobly.com.br';
                $this->secureBaseUrl = 'https://rocket:rock4me@staging01-secure.mobly.com.br';
                break;
            case 'dev':
                $this->baseUrl = 'http://alice.mobly.dev';
                $this->secureBaseUrl = 'https://alice-secure.mobly.dev';
                break;
            default:
                throw new \RuntimeException('Variavel de ambiente APPLICATION_ENV possui valor invalido');
        }

        return $scope->getEnvironment();
    }

    /**
     * @return $this
     */
    protected function setSslBaseUrl()
    {
        $this->minkContext->setMinkParameter('base_url', $this->secureBaseUrl);

        static::$sharedUrl = $this->minkContext->getMinkParameter('base_url');

        return $this;
    }

    /**
     * @param $version
     * @return $this|null
     */
    protected function selectMultiStagingServerVersion($version)
    {
        if ($_SERVER['APPLICATION_ENV'] != 'staging') {
            //don't bother in this case
            return null;
        }
        if (!in_array((int)$version, [1,2,3,4,5])) {
            //don't bother either
            return null;
        }

        $host = preg_replace('/(staging)/', '${1}0'. $version, $this->minkContext->getMinkParameter('base_url'));

        $this->minkContext->setMinkParameter(
            'base_url',
            $host
        );

        static::$sharedUrl = $this->minkContext->getMinkParameter('base_url');

        return $this;
    }
}
