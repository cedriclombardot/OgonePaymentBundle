<?php

namespace Pilot\OgonePaymentBundle\Builder;

use Pilot\OgonePaymentBundle\Config\ConfigurationContainer;

class TransactionFormBuilder
{
    protected $formFactory;

    protected $form;

    protected $onEnd;

    protected $secureConfigurationContainer;

    public function __construct(\Symfony\Component\Form\FormFactoryInterface $formFactory, ConfigurationContainer $secureConfigurationContainer)
    {
        $this->formFactory = $formFactory;
        $this->form = $this->formFactory->createNamedBuilder(null, 'form');

        $this->secureConfigurationContainer = $secureConfigurationContainer;
    }

    public function getForm()
    {
        return $this->form->getForm();
    }

    public function build(ConfigurationContainer $configurationContainer)
    {
        $fields = $configurationContainer->all();

        if (!isset($fields['amount'])) {
            $fields['amount'] = 0;
        }

        foreach ($fields as $key => $value) {
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d');
            }

            $this->form->add($configurationContainer->findProperty($key), 'hidden', array('data' => $this->stripAccents($value)));
        }

        $this->form->add('SHASign', 'hidden', array('data' => $this->getSHASign($configurationContainer)));

        return $this;
    }

    protected function stripAccents($str)
    {
        $str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
        $str = preg_replace('#\&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring)\;#', '\1', $str);
        $str = preg_replace('#\&([A-za-z]{2})(?:lig)\;#', '\1', $str);
        $str = preg_replace('#\&[^;]+\;#','', $str);

        return $str;
    }

    protected function getSHASign(ConfigurationContainer $configurationContainer)
    {
        if (!$this->secureConfigurationContainer->getShaInKey()) {
            throw new \InvalidArgumentException('You should configure your sha_in_key');
        }

        $toHash = '';

        $properties = $configurationContainer->all();
        if (!isset($properties['amount'])) {
            $properties['amount'] = 0;
        }
        ksort($properties);

        foreach ($properties as $key => $val) {
            if ($val instanceof \DateTime) {
                $val = $val->format('Y-m-d');
            }

            $toHash .= strtoupper($key).'='.$this->stripAccents($val).$this->secureConfigurationContainer->getShaInKey();
        }

        return strtoupper(hash($this->secureConfigurationContainer->getAlgorithm(), $toHash));
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
}
