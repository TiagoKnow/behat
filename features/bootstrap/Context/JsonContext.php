<?php
namespace Context;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Testwork\Tester\Result\TestResult;

final class JsonContext extends AbstractContext
{
    /**
     * @Then /^(?:eu )?devo ver JSON contendo chave "(?<chave>[^"]+)"(?: com valor "(?<valor>[^"]+)")?$/
     */
    public function euEstouNaHomepage($chave, $valor = null)
    {
        $plainJson = $this->minkContext->getSession()->getPage()->getContent();
        $json = json_decode($plainJson, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \Exception('Página não retornou um JSON válido');
        }

        if (!array_key_exists($chave, $json)) {
            throw new \Exception('JSON não contém a chave "' . $chave . '"');
        }
        if ($valor !== null && $valor !== $json[$chave]) {
echo $plainJson . PHP_EOL;
            throw new \Exception(sprintf('JSON contém a chave "%s" com valor %s (esperado %s)', $chave, var_export($json[$chave], true), var_export($valor, true)));
        }
    }

}
