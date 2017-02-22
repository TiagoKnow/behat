<?php

namespace Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use Context\AbstractContext;
use Behat\Mink\Session;
use SensioLabs\Behat\PageObjectExtension\PageObject\Factory;

abstract class AbstractPage extends Page
{
    use FakerTrait;

    /**
     * @param Session $session
     * @param Factory $factory
     * @param array   $parameters
     */
    public function __construct(Session $session, Factory $factory, array $parameters = array())
    {
        parent::__construct($session, $factory, ['base_url' => AbstractContext::$sharedUrl]);
    }

    /**
     * expect [fakerType =>[params]] | fakerType
     * @param null $value
     * @param string|array $fakerType
     * @return mixed|null
     */
    protected function setValueOrUseFaker($value = null, $fakerType = null)
    {
        if (null === $value) {
            if (is_array($fakerType)) {
                return call_user_func_array([$this->getFaker(), key($fakerType)], $fakerType[key($fakerType)]);
            }

            return $this->getFaker()->$fakerType;
        }

        return $value;
    }

    /**
     * @param $locator
     * @param $value
     */
    protected function fillFieldWithMask($locator, $value)
    {
        $element = $this->find('css', $locator);

        if (!is_null($element)) {
            $element->click();
            $element->setValue($value);
        }
    }

    protected function verifyUrl(array $urlParameters = array())
    {
        // do nothing thank GOD !
    }
}