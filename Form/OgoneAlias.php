<?php

namespace Cedriclombardot\OgonePaymentBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class OgoneAlias extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $years = range(date('Y'), date('Y') + 5);
       $month = range(1, 12);

       $builder
           ->add('brand', 'choice', array('choices' => array(
                'VISA' => 'VISA'
           )))
           ->add('card_number', 'text', array())
           ->add('card_name', 'text')
           ->add('expiration_date_year', 'choice', array('choices' => array_combine($years, $years)))
           ->add('expiration_date_month', 'choice', array('choices' => array_combine($month, $month)))
           ;
    }

    public function getName()
    {
        return 'ogone_alias';
    }

}
