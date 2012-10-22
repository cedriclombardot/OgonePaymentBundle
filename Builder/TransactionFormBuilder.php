<?php

namespace Cedriclombardot\OgonePaymentBundle\Builder;

use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;

class TransactionFormBuilder
{
    protected $formFactory;

    protected $form;

    protected $onEnd;

    protected $secureConfigurationContainer;

    public function __construct(\Symfony\Component\Form\FormFactoryInterface $formFactory, ConfigurationContainer $secureConfigurationContainer)
    {
        $this->formFactory = $formFactory;
        $this->form = $this->formFactory
                           ->createNamedBuilder(null, 'form');

        $this->secureConfigurationContainer = $secureConfigurationContainer;
    }

    public function getForm()
    {
        return $this->form->getForm();
    }

    public function build(ConfigurationContainer $configurationContainer)
    {
        foreach ($configurationContainer->all() as $key => $value) {
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d');
            }

            $this->form->add($configurationContainer->findProperty($key), 'hidden', array('data' => $value));
        }

        $this->form->add('SHASign', 'hidden', array('data' => $this->getSHASign($configurationContainer)));

        return $this;
    }

    protected function getSHASign(ConfigurationContainer $configurationContainer)
    {
        if (!$this->secureConfigurationContainer->getShaInKey()) {
            throw new \InvalidArgumentException('You should configure your sha_in_key');
        }

        $toHash = '';

        $properties = $configurationContainer->all();
        ksort($properties);

        foreach ($properties as $key => $val) {
            if ($val instanceof \DateTime) {
                $val = $val->format('Y-m-d');
            }

            $toHash .= strtoupper($key).'='.$val.$this->secureConfigurationContainer->getShaInKey();
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
