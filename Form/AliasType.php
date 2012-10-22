<?php

namespace Cedriclombardot\OgonePaymentBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class AliasType extends AbstractType
{
    protected $brands = array('VISA');

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $months = array('01','02','03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
        $years  = range(date('y'), date('y') + 4 );

        $builder->add('card_brand', 'choice', array(
            'choices'  => array_combine($this->brands, $this->brands),
            'required' => true,
        ))
        ->add('card_number', 'number', array(
            'required' => true,
        ))
        ->add('card_name', 'text', array(
            'required' => true,
        ))
        ->add('date_month', 'choice', array(
            'choices'  => array_combine($months, $months),
            'required' => true,
        ))
        ->add('date_year', 'choice', array(
            'choices'  => array_combine($years, $years),
            'required' => true,
        ))
        ;
    }

    public function getName()
    {
        return 'ogone_alias';
    }
}