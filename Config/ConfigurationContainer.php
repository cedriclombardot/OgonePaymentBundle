<?php

namespace Pilot\OgonePaymentBundle\Config;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ConfigurationContainer extends ParameterBag
{
    protected $properties = array(
       'PSPID', 'ORDERID', 'AMOUNT', 'AMOUNTHTVA', 'CURRENCY', 'LANGUAGE',
       'TITLE', 'BGCOLOR', 'TXTCOLOR', 'TBLBGCOLOR', 'TBLTXTCOLOR',
       'BUTTONBGCOLOR', 'BUTTONTXTCOLOR', 'FONTTYPE', 'LOGO', 'TP',
       'ACCEPTURL', 'DECLINEURL', 'EXCEPTIONURL', 'CANCELURL', 'BACKURL',
       'HOMEURL', 'CATALOGURL', 'USERID', 'COM', 'OPERATION', 'ECOM_SHIPTO_TELECOM_PHONE_NUMBER',
       'ECOM_SHIPTO_ONLINE_EMAIL', 'ECOM_SHIPTO_POSTAL_NAME_PREFIX', 'ECOM_SHIPTO_POSTAL_NAME_FIRST',
       'ECOM_SHIPTO_POSTAL_NAME_LAST', 'ECOM_SHIPTO_COMPANY', 'ECOM_SHIPTO_POSTAL_STREET_LINE1', 'ECOM_SHIPTO_POSTAL_STREET_LINE2',
       'ECOM_SHIPTO_POSTAL_CITY', 'ECOM_SHIPTO_POSTAL_COUNTRYCODE', 'ECOM_SHIPTO_POSTAL_POSTALCODE', 'ECOM_SHIPTO_POSTAL_POSTALCODE',
       'ECOM_BILLTO_POSTAL_STREET_LINE1', 'ECOM_BILLTO_POSTAL_STREET_LINE2', 'ECOM_BILLTO_POSTAL_CITY',
       'ECOM_BILLTO_POSTAL_COUNTRYCODE', 'ECOM_BILLTO_POSTAL_POSTALCODE', 'PM', 'GENDER',
       'CIVILITY', 'CN', 'ECOM_BILLTO_POSTAL_NAME_FIRST', 'ECOM_BILLTO_POSTAL_NAME_LAST', 'EMAIL',
       'OWNERZIP', 'OWNERADDRESS', 'OWNERADDRESS2', 'OWNERCTY', 'OWNERTOWN', 'OWNERTELNO', 'OWNERTELNO2', 'ECOM_SHIPTO_DOB',
       'ACCEPTURL', 'DECLINEURL', 'EXCEPTIONURL', 'CANCELURL', 'BACKURL',
       'ALIASOPERATION', 'ALIAS', 'ALIASUSAGE',
    );

    protected $router;

    protected $templateRoute;

    public function __construct(array $defaults = array(), $router = null, $templateRoute = null)
    {
        $this->setRouter($router, $templateRoute);

        foreach ($defaults as $key => $val) {
            $method = 'set'.\Symfony\Component\DependencyInjection\Container::camelize($key);
            $this->$method($val);
        }
    }

    public function setRouter($router, $templateRoute)
    {
        $this->router = $router;
        $this->templateRoute = $templateRoute;

        return $this;
    }

    public function setTemplateRoute($templateRoute)
    {
        $this->templateRoute = $templateRoute;

        return $this;
    }

    /**
    * @param string $twigPath The template name
    * @param array $context The twig variables context
    * @param string $urlPrefix if specified use your prefix instead generate absolute urls
    *
    * @return self
    */
    public function setTemplate($twigPath, array $context = array(), $urlPrefix = null)
    {
        if (!$urlPrefix) {
            $url = $this->router->generate($this->templateRoute, array('twigPath' => $twigPath, 'context' => base64_encode(json_encode($context))), true);
        } else {
            $url = $urlPrefix.$this->router->generate($this->templateRoute, array('twigPath' => $twigPath, 'context' => base64_encode(json_encode($context))));
        }

        return $this->setTP($url);
    }

    public function onEnd($object)
    {
        $this->onEnd = $object;

        return $this;
    }

    public function end()
    {
        $toReturn = $this->onEnd;
        $this->onEnd = null;

        return $toReturn;
    }

    public function __call($method, array $args)
    {
        if (preg_match('/^(set|get)(.+)/', $method, $matches)) {
            $property = $this->findProperty($matches[2]);

            if (!$property) {
                throw new \InvalidArgumentException('Cannot find propery "'.$matches[2].'"');
            }

            $call = $matches[1];

            if ($call == 'get') {
              return $this->$call($property);
            }

            if ('' != $args[0]) {
               $this->$call($property, $args[0]);
            }

            return $this;
        }
    }

    public function findProperty($name)
    {
        if (in_array(strtoupper($name), $this->properties)) {
            return strtoupper($name);
        }

        return false;
    }
}
