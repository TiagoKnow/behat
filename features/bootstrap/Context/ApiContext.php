<?php
namespace Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class ApiContext implements Context
{
    public $urlBase;
    public $url;
    public $metodo;
    public $codigo;
    public $cabecalho;
    public $resposta = array();
    public $conteudoJson;
    public $dadosPost;
    public $cabecalhoHttp = array('accept' => 'application/json');

    /**
     * @BeforeScenario
     */
    public function prepararCenario(BeforeScenarioScope $scope)
    {
        switch ($_SERVER['ENVIRONMENT']) {
        case 'production':
            $baseUrl = 'http://www.mobly.com.br';
        case 'testing':
            $baseUrl = 'http://alice-staging.mobly.com.br';
            break;
        case 'development':
            $baseUrl = 'http://alice.mobly.dev';
            break;
        default:
            throw new \RuntimeException('Variavel de ambiente ENVIRONMENT possui valor invalido');
        }
    }

    /**
     * @Given /^(?:|Eu )acesso a api "(?<url>[^"]+)"(?: com o metodo "(?<metodo>[^"]+)")?$/i
     */
    public function acessoApi($url, $metodo = 'GET')
    {
        if (!preg_match('#^\w+://#', $url)) {
            $url = $this->urlBase . $url;
        }
        $this->url = $url;
        $this->metodo = $metodo;
    }

    /**
     * @When /^(?:|Eu )recebo a resposta$/i
     */
    public function EuReceboAResposta()
    {
        $curl = curl_init();

        if ($this->dadosPost) {
            $this->adicionarCabecalhoHttp('Content-length', strlen($this->dadosPost));
        }

        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_PORT, 80);
        curl_setopt($curl, CURLOPT_USERPWD, null);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->metodo);
        curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->montaCabecalhoHttp());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->dadosPost);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

        // Obter resposta
        $resposta = array(
            'status'    => null,
            'cabecalho' => array(),
            'conteudo'  => false
        );

        if (!$curl) {
            throw new \Exception('Erro ao inicializar Curl');
        }

        $retorno = curl_exec($curl);

        if (curl_errno($curl) != CURLE_OK) {
            throw new \Exception('Erro ao executar Curl');
        }
        $info = curl_getinfo($curl);

        $tamanho_header = $info['header_size'];
        $this->resposta['cabecalho'] = trim(substr($retorno, 0, $tamanho_header));
        $this->resposta['conteudo'] = substr($retorno, $tamanho_header);
        $this->resposta['codigo'] = $info['http_code'];
    }

    /**
     * @Then /^(?:|Eu )obtenho o codigo de resposta "(?<codigo>\d{1,3})"$/i
     */
    public function RespostaTemCodigo($codigo)
    {
        switch (strlen($codigo)) {
            case 3:
                if ($this->resposta['codigo'] != $codigo) {
                    throw new \Exception('Codigo de resposta obtido: ' . $this->resposta['codigo']);
                }
                break;
            case 1:
                if (substr($this->resposta['codigo'], 0, 1) != $codigo) {
                    throw new \Exception('Codigo de resposta obtido: ' . $this->resposta['codigo']);
                }
                break;
            default:
                throw new \InvalidArgumentException('Codigo deve conter 1 ou 3 digitos');
                break;
        }

    }

    /**
     * @Then /^(?:|Eu )obtenho um JSON valido$/i
     */
    public function ObtenhoUmJSONValido()
    {
        $this->conteudoJson = json_decode($this->resposta['conteudo']);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \Exception('JSON invalido');
        }
    }

    /**
     * @Then /^o JSON obtido possui campo "(?<campo>[^"]+)"(?: com valor "(?<valor>[^"]+)")?$/i
     */
    public function jsonPossuiCampoComValor($campo, $valor = null)
    {
        $valorObtido = '$this->conteudoJson->' . $campo;
        if (!assert('isset(' . $valorObtido . ')')) {
            throw new \Exception('O JSON obtido nao possui o campo ' . $campo);
        }
        if ($valor !== null) {
            $valorObtido = eval('return ' . $valorObtido . ';');
            if ($valorObtido != $valor) {
                throw new \Exception('O JSON obtido possui o campo ' . $campo . ' com valor ' . var_export($valorObtido, true) . ', que é diferente do valor esperado ' . var_export($valor, true));
            }
        }
    }

    /**
     * @Given /^(?:|Eu )envio os dados "(?<dados>.+?)"$/i
     */
    public function definirDadosPost($dados)
    {
        $this->dadosPost = $dados;
    }

    /**
     * @Given /^(?:|Eu )envio o cabecalho "(?<cabecalho>[^"]+)" com valor "(?<valor>.+?)"$/i
     */
    public function adicionarCabecalhoHttp($cabecalho, $valor)
    {
        $this->cabecalhoHttp[strtolower($cabecalho)] = $valor;
    }

    private function montaCabecalhoHttp()
    {
        $array_header = array();

        foreach ($this->cabecalhoHttp as $header => $valor) {
            array_push($array_header, sprintf("%s: %s", $header, $valor));
        }

        return $array_header;
    }

    public function restaurarCabecalhoHttp()
    {
        $this->cabecalhoHttp = array('accept' => 'application/json');
    }
}
