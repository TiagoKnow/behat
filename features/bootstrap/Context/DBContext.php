<?php
namespace Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Context\Context;

final class DBContext implements Context
{
    protected $config = array();

    /**
     * Carrega as configuracoes de banco
     * @BeforeScenario
     */
    /*public function carregar_configuracoes(BeforeScenarioScope $scope)
    {
        if (!defined('BASEPATH')) {
            define('BASEPATH', __DIR__);
        }
        include(__DIR__ . "{caminho_config_banco}");

        $this->config = $config['db'];
    }*/

    /**
     * Conecta em um banco para obter uma conexao PDO
     * @param string $banco Nome do banco
     * @param string $tipo_conexao Tipo de conexao (master ou slave)
     * @param array $options Opcoes para construir a conexao com PDO
     * @return PDO
     */
    public function conectar($banco, $tipo_conexao = 'master', array $options = array())
    {
        $chave_conexao = $banco . '-' . $tipo_conexao;
        if (!isset($this->config[$chave_conexao])) {
            throw new \RuntimeException('Conexao nao encontrada para: ' . $chave_conexao);
        }
        $params = parse_url($this->config[$chave_conexao]);

        switch ($params['scheme']) {
        case 'mysql':
            $dsn = $this->montar_dsn_mysql($params);
            break;
        default:
            throw new \RuntimeException('DBContext nao esta preparado para montar DSN de bancos do tipo ' . $params['scheme']);
        }

        return new \PDO($dsn, $params['user'], $params['pass'], $options);
    }

    /**
     * Monta o DSN para conectar em um banco MySQL com PDO
     * @param $params Parametros de conexao
     * @return string
     */
    protected function montar_dsn_mysql(array $params)
    {
        $partes = array();
        $partes[] = 'host=' . $params['host'];

        if (isset($params['port'])) {
            $partes[] = 'port=' . $params['port'];
        }
        if (isset($params['path'])) {
            $partes[] = 'dbname=' . trim($params['path'], '/');
        }
        if (isset($params['query'])) {
            $query_array = array();
            parse_str($params['query'], $query_array);

            if (isset($query_array['charset'])) {
                $partes[] = 'charset=' . $query_array['charset'];
            }
        }

        return 'mysql:' . implode(';', $partes);
    }

}
