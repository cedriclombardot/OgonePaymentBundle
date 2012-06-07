<?php

namespace Cedriclombardot\OgonePaymentBundle\Manager;

class ApiRequest
{
    const ROW_SEP = "\n";

    protected $api;

    protected $rows;

    public function __construct($api)
    {
        $this->api = $api;
    }

    /**
     * OHL: Login information header
     *
     * @return string OHL;PSPID;PSWD;USERTYPE;USERID;
     */
    public function getFileOHL()
    {
        return implode(';', array(
          'OHL',
          $this->api->getPspid(),
          $this->api->getUserPassword(),
          '',
          $this->api->getUserId(),
        )).';';
    }

    /**
     * OHF: File information header
     *
     * @return string OHF;FILE_REFERENCE;TRANSACTION_CODE;OPERATION;NB_PAYMENTS;
     */
    public function getFileOHF($fileRef, $transactionCode)
    {
        if (null === $fileRef) {
           $fileRef = 'ApiRequest'.time();
        }

        return implode(';', array(
          'OHF',
          $fileRef,
          $transactionCode,
          '',
          '',
        )).';';
    }

    public function addFileRow($row)
    {
        $this->rows[] = $row;

        return $this;
    }

    public function call()
    {
        $file  = $this->getFileOHL().self::ROW_SEP.$this->getFileOHF();
        $file .= implode(self::ROW_SEP, $this->rows);
    }

}
