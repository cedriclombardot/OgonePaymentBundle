<?php

namespace Pilot\OgonePaymentBundle\Exception;

class InvalidBatchDatasException extends \InvalidArgumentException
{
    protected $errors = array();

    public function __construct($message, $code  = 0, \Exception $previous = NULL)
    {
        $this->errors = $message;

        return parent::__construct('Error processing batch ', $code, $previous);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getErrorsMessages()
    {
        $messages = array();

        foreach ($this->getErrors() as $error) {
            $xml = $error->xpath('ERROR');
            $messages[] = (string) $xml[0];
        }

        return $messages;
    }

}
