<?php

namespace Pilot\OgonePaymentBundle\Batch;

use Pilot\OgonePaymentBundle\File\OgoneDownloadedFile;

class FileDownloader extends BatchRequest
{
    const URL = 'https://secure.ogone.com/ncol/%env%/payment_download_ncp.asp';

    public function getFile($fileReference)
    {
        $request = new \Buzz\Message\Form\FormRequest();
        $request->fromUrl($this->getUrl());

        $request->setField('ID', $fileReference);
        $request->setField('PSPID', $this->configurationContainer->get('PSPID'));
        $request->setField('USERID', $this->secureConfigurationContainer->get('USERID'));
        $request->setField('PSWD', $this->secureConfigurationContainer->get('USERPASSWORD'));
        $request->setField('Format', 'XML');

        return new OgoneDownloadedFile($this->processFormRequest($request));
    }

    protected function getUrl()
    {
        return strtr(self::URL, array('%env%' => $this->ogoneEnv));
    }
}
