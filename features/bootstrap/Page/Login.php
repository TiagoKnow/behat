<?php

namespace Page;

class Login extends AbstractPage
{
    /**
     * @var string $path
     */
    public $path;

    const LOJAMOBLY_PREFIX_FORM = 'LoginLojaMoblyForm';

    const LOJAMOBLY_PATH = '/lojamobly/account/login/';

    const MOBLY_PREFIX_FORM = 'LoginForm';

    const MOBLY_PATH = '/customer/account/login/';

    protected $prefix;

    /**
     * @param null $login
     * @return $this
     */
    public function setLogin($login = null)
    {
        $value = $this->setValueOrUseFaker($login, 'email');

        $this->fillField($this->prefix . '_email', $value);

        return $this;
    }

    /**
     * @param null $password
     * @return $this
     */
    public function setPassword($password = null)
    {
        $value = $this->setValueOrUseFaker($password);

        $this->fillField($this->prefix . '_password', $value);

        return $this;
    }

    public function send()
    {
        $element = $this->find('css', '.sel-login-button');

        if (!is_null($element)) {
            $element->click();
        }
    }

    public function setLojaMoblyPrefix()
    {
        $this->prefix = static::LOJAMOBLY_PREFIX_FORM;
        $this->path = static::LOJAMOBLY_PATH;
    }

    public function setMoblyPrefix()
    {
        $this->prefix = static::MOBLY_PREFIX_FORM;
        $this->path = static::MOBLY_PATH;
    }



}