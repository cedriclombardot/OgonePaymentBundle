<?php

namespace Pilot\OgonePaymentBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Translation\TranslatorInterface;
use Pilot\OgonePaymentBundle\Batch\TransactionManager;

class AliasType extends AbstractType
{
    protected $translator;
    protected $transactionManager;
    protected $brands = array();

    public function __construct(TranslatorInterface $translator, TransactionManager $transactionManager, array $brands)
    {
        $this->translator = $translator;
        $this->transactionManager = $transactionManager;
        $this->brands = $brands;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $months = array('01','02','03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
        $years  = range(date('y'), date('y') + 4 );

        $builder
            ->add('card_brand', 'choice', array(
                'choices'  => array_combine($this->brands, $this->brands),
                'required' => true,
            ))
            ->add('card_number', 'text', array(
                'required' => true,
            ))
            ->add('card_name', 'text', array(
                'required' => true,
            ))
            ->add('card_cvv', 'number', array(
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
        $transactionManager = $this->transactionManager;

        $builder->addValidator(new CallbackValidator(function(FormInterface $form) use ($translator, $transactionManager) {
            if ($form->get('date_year')->getData().$form->get('date_month')->getData() < date('ym')) {
                $form->get('date_month')->addError( new FormError( $translator->trans("alias.date_expired", array(), 'ogone') ) );

                return;
            }

            if (mb_strlen($form->get('card_cvv')->getData()) != 3) {
                $form->get('card_cvv')->addError( new FormError( $translator->trans("card_cvv.invalid", array(), 'ogone') ) );

                return;
            }

            // Check card using ogone
            try {
                $authorisation = $transactionManager
                    ->checkAuthorisation(
                        0,
                        $form->get('card_brand')->getData(),
                        $form->get('card_number')->getData(),
                        $form->get('date_month')->getData().$form->get('date_year')->getData(),
                        $form->get('card_name')->getData(),
                        $form->get('card_cvv')->getData(),
                        null // No order id
                    )
                ;
            } catch (\Pilot\OgonePaymentBundle\Exception\InvalidBatchDatasException $e) {

                foreach ($e->getErrors() as $error) {
                    $code = $error->xpath('NCERROR');
                    $code = (string) $code[0];
                    $field = $form;

                    $message =  $translator->trans('error.'.$code, array(), 'ogone');

                    if ($message == 'error.'.$code) { // Not yet translated
                        $ogoneMessage = $error->xpath('ERROR');
                        $ogoneMessage = (string) $ogoneMessage[0];

                        $message = sprintf('[%s] %s', $code, $ogoneMessage);
                    }

                    if ($code == 50001002) {
                        $field = $form->get('card_number');
                    }

                    $field->addError( new FormError( $message ) );
                }
            }
        }));
    }

    public function getName()
    {
        return 'ogone_alias';
    }
}
