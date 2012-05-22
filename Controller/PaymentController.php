<?php

namespace Cedriclombardot\OgonePaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cedriclombardot\OgonePaymentBundle\Propel\OgoneClientQuery;

class PaymentController extends Controller
{
    public function indexAction()
    {
        $client = OgoneClientQuery::create()
                       ->filterByEmail('test@test.com')
                       ->findOneOrCreate();
        $client->save();

        $transaction = $this->get('ogone.transaction_builder')
                            ->order()
                                ->setClient($client)
                                ->setAmount(100)
                            ->end()
                            ->configure()
                                ->setBgColor("red")
                                ->setAcceptUrl($this->generateUrl('ogone_payment_feedback', array(), true))
                                ->setDeclineUrl($this->generateUrl('ogone_payment_feedback', array(), true))
                                ->setExceptionUrl($this->generateUrl('ogone_payment_feedback', array(), true))
                                ->setCancelUrl($this->generateUrl('ogone_payment_feedback', array(), true))
                                ->setBackUrl($this->generateUrl('ogone_payment_feedback', array(), true))
                            ->end()
                            ;
        $form = $transaction->getForm();

        return $this->render(
            'CedriclombardotOgonePaymentBundle:Payment:index.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function feedbackAction()
    {
        if (!$this->get('ogone.feedbacker')->isValidCall()) {
            throw $this->createNotFoundException();
        }

        $this->get('ogone.feedbacker')->updateOrder();

        return $this->render(
            'CedriclombardotOgonePaymentBundle:Payment:feedback.html.twig'
        );
    }
}
