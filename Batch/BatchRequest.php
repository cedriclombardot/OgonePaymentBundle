<?php

namespace Pilot\OgonePaymentBundle\Batch;

use Pilot\OgonePaymentBundle\Config\ConfigurationContainer;
use Pilot\OgonePaymentBundle\Config\SecureConfigurationContainer;
use Pilot\OgonePaymentBundle\Exception\InvalidBatchDatasException;

class BatchRequest
{
    const URL = 'https://secure.ogone.com/ncol/%env%/AFU_agree.asp';

    const PROCESS_MODE_CHECK = 'CHECK';
    const PROCESS_MODE_SEND = 'SEND';
    const PROCESS_MODE_PROCESS = 'PROCESS';
    const PROCESS_MODE_CANCEL = 'CANCEL';

    const FILE_FOOTER = "\nOTF;";

    protected $ogoneEnv;

    protected $configurationContainer;

    protected $secureConfigurationContainer;

    protected $lastpfId;

    public function __construct($ogoneEnv, ConfigurationContainer $configurationContainer, SecureConfigurationContainer $secureConfigurationContainer)
    {
        $this->ogoneEnv = $ogoneEnv;
        $this->configurationContainer = $configurationContainer;
        $this->secureConfigurationContainer = $secureConfigurationContainer;
    }

    public function check($datas, $fileReference = null)
    {
        $checkResponse = $this->run($datas, $fileReference, self::PROCESS_MODE_CHECK);

        if ($errors = $this->detectErrors($checkResponse, 'FORMAT_CHECK/FORMAT_CHECK_ERROR')) {
            throw new InvalidBatchDatasException($errors);
        }

        return $checkResponse;
    }

    public function send($datas, $fileReference = null, $pfId = null)
    {
        $sendResponse = $this->run($datas, $fileReference, self::PROCESS_MODE_SEND, $pfId);

        if ($errors = $this->detectErrors($sendResponse, 'FORMAT_SEND/FORMAT_SEND_ERROR')) {
            throw new InvalidBatchDatasException($errors);
        }

        return $sendResponse;
    }

    public function process($datas, $fileReference = null)
    {
        $fileReference = $this->getFileReference($fileReference);

        // 1 step : Check
        $checkResponse = $this->check($datas, $fileReference);
        $pfId = $this->getFileIdFromResponse($checkResponse);

        // 2 Step : SEND
        $sendResponse = $this->send($datas, $fileReference, $pfId);

        // 3 step : PROCESS
        $processResponse = $this->run(null, $fileReference, self::PROCESS_MODE_PROCESS, $pfId);

        return $processResponse;
    }

    public function getFileIdFromResponse($response)
    {
        $pfId = $this->getXmlNode($response, 'FORMAT_CHECK/FILEID');
        $this->lastpfId = (string) $pfId[0];

        return $this->lastpfId;
    }

    public function getLastPfId()
    {
        return $this->lastpfId;
    }

    protected function run($datas, $fileReference, $mode, $pfId = null)
    {
        $fileReference = $this->getFileReference($fileReference);

        $request = $this->buildRequest($datas, $fileReference, $mode, $pfId);

        return $this->processFormRequest($request);
    }

    protected function getXmlNode(\Buzz\Message\Response $response, $xpath)
    {
        $xml = new \SimpleXMLElement($response->getContent());

        return $xml->xpath($xpath);
    }

    protected function detectErrors(\Buzz\Message\Response $response, $xpath)
    {
        $errors = $this->getXmlNode($response, $xpath);

        if (count($errors) > 0) {
            return $errors;
        }

        return false;
    }

    protected function processFormRequest(\Buzz\Message\Form\FormRequest $request)
    {
        $response = new \Buzz\Message\Response();

        $client = new \Buzz\Client\Curl();
        if ('test' == $this->ogoneEnv) {
            $client->setVerifyPeer(false);
        }

        $client->setTimeout(30);
        $client->send($request, $response);

        return $response;
    }

    protected function getFileReference($fileReference = null)
    {
        if (!$fileReference) {
            $fileReference = 'FILE'.time();
        }

        return $fileReference;
    }

    protected function buildRequest($datas, $fileReference, $processMode, $pfId = null)
    {
        $request = new \Buzz\Message\Form\FormRequest();
        $request->fromUrl($this->getUrl());
        if ($datas) {
            $request->setField('FILE', $datas.self::FILE_FOOTER);
        }

        if ($pfId) {
            $request->setField('PFID', $pfId);
        }

        $request->setField('FILE_REFERENCE', $fileReference);
        $request->setField('PSPID', $this->configurationContainer->get('PSPID'));
        $request->setField('USERID', $this->secureConfigurationContainer->get('USERID'));
        $request->setField('PSWD', $this->secureConfigurationContainer->get('USERPASSWORD'));
        $request->setField('REPLY_TYPE', 'XML');
        $request->setField('MODE', 'SYNC');
        $request->setField('PROCESS_MODE', $processMode);

        return $request;
    }

    protected function getUrl()
    {
        return strtr(self::URL, array('%env%' => $this->ogoneEnv));
    }
}
