<?php

namespace Pilot\OgonePaymentBundle\File;

use Buzz\Message\Response;

class OgoneDownloadedFile
{
    protected $response;

    protected $payments = array();

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getPaymentByRef($ref)
    {
        if (isset($this->payments[$ref])) {
            return $this->payments[$ref];
        }

        $datas = $this->getDatas('PAYMENT[@REF='.$ref.']');

        if (!isset($datas[0])) {
            return false;
        }

        return $this->payments[$ref] = new OgoneDownloadedPayment($datas[0]);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->getResponse(), $method), $args);
    }

    protected function getDatas($xpath)
    {
        $xml = new \SimpleXMLElement($this->getContent());
        $datas = $xml->xpath($xpath);

        return $datas;
    }
}
