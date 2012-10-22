<?php

namespace Cedriclombardot\OgonePaymentBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Translation\TranslatorInterface;

class AliasType extends AbstractType
{
    protected $translator;

    public function __construct(TranslatorInterface $translator, array $brands)
    {
        $this->translator = $translator;
        $this->brands = $brands;
    }

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

        $translator = $this->translator;

        $builder->addValidator(new CallbackValidator(function(FormInterface $form) use ($translator) {
            if ($form->get('date_year')->getData().$form->get('date_month')->getData() < date('ym')) {
                $form->get('date_month')->addError( new FormError( $translator->trans("alias.date_expired", array(), 'ogone') ) );
            }

        }));
    }

    public function getName()
    {
        return 'ogone_alias';
    }
}
