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

    protected $config;

    protected $env;

    public static $sharedUrl;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->initConfig();
        $environment = $this->getEnvironment($scope);
        $this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');
        $this->minkContext->setMinkParameter('base_url', $this->baseUrl);

        static::$sharedUrl = $this->minkContext->getMinkParameter('base_url');

        if ($this->minkContext->getSession()->getDriver() instanceof \Behat\Mink\Driver\Selenium2Driver) {

            $this->minkContext->getSession()->resizeWindow(
                $this->config->windowSize->width ? (int)$this->config->windowSize->width: 1920,
                $this->config->windowSize->height ? (int)$this->config->windowSize->height: 1080,
                'current'
            );
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
     * Charge configuration file
     */
    protected function initConfig()
    {
        if (null === $this->config) {
            $this->config = json_decode(json_encode(include getcwd() . '/config/config.php'));
        }
    }

    /**
     * @param BeforeScenarioScope $scope
     * @return \Behat\Testwork\Environment\Environment
     */
    protected function getEnvironment(BeforeScenarioScope $scope)
    {
        if (!in_array($_SERVER['APPLICATION_BEHAT_ENV'], ['dev', 'stg01', 'stg02', 'stg03', 'stg04', 'live'])) {
            throw new \RuntimeException('Variavel de ambiente APPLICATION_BEHAT_ENV possui valor invalido');
        }

        $this->env = $env = $_SERVER['APPLICATION_BEHAT_ENV'];

        $this->baseUrl = $this->config->environments->{$env}->baseUrl;
        $this->secureBaseUrl = $this->config->environments->{$env}->secureUrl;

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
}
