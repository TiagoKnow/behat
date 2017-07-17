<?php
namespace Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Context\Context;

final class DBContext extends AbstractContext
{
    public function connect()
    {
        $this->db = new \PDO("mysql:host={$this->config['host']};dbname={$this->config['database']}", $this->config['username'], $this->config['password'],
            array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            )
        );
    }

}
