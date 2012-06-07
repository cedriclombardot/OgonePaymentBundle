<?php

namespace Cedriclombardot\OgonePaymentBundle\Manager;

use Cedriclombardot\OgonePaymentBundle\Propel\OgoneAlias;

class AliasManager
{
    const METHOD_ADDALIAS = 'ADDALIAS';
    const METHOD_DELALIAS = 'DELALIAS';

    protected $alias;

    protected $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function withAlias(OgoneAlias $alias)
    {
        $this->alias = $alias;

        return $this;
    }

    public function update(array $form_datas)
    {
        if (!$this->alias->isNew()) {
            $this->delete($form_datas);
        }

        return $this->callOgone(AliasManager::METHOD_ADDALIAS, $form_datas);
    }

    public function delete(array $form_datas)
    {
        return $this->callOgone(AliasManager::METHOD_DELALIAS, $form_datas);
    }

    protected function buildOgoneCsv($method, $form_datas)
    {
        $line = array(
          $method,
          $this->alias->getName(),
          $form_datas['card_name'],
          $form_datas['card_number'],
          str_pad($form_datas['expiration_date_month'],2,"0",STR_PAD_LEFT).substr($form_datas['expiration_date_year'], 2),
          $form_datas['brand'],
          $this->api->getPspid()
        );

        return implode(';', $line).';';
    }

    protected function callOgone($method, array $form_datas)
    {
        $this->api->createRequest()
                  ->addFileRow($this->buildOgoneCsv($method, $form_datas))
                  ->call();

        var_dump($this->buildOgoneCsv($method, $form_datas)); die;

    }
}
