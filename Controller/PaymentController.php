<?php

namespace Cedriclombardot\OgonePaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cedriclombardot\OgonePaymentBundle\Propel\OgoneClientQuery;
use Cedriclombardot\OgonePaymentBundle\Propel\OgoneAliasQuery;
use Cedriclombardot\OgonePaymentBundle\Propel\OgoneAliasPeer;

use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function indexAction()
    {
        $client = OgoneClientQuery::create()
                       ->filterByEmail('test@test.com')
                       ->findOneOrCreate();

        $client->save();

        if ($this->container->getParameter('ogone.use_aliases')) {
            $alias = OgoneAliasQuery::create()
                       ->filterByOgoneClient($client)
                       ->filterByOperation(OgoneAliasPeer::OPERATION_BYMERCHANT)
                       ->filterByName('ABONNEMENT')
                       ->findOneOrCreate();

             $alias->save();
        }

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

        if ($this->container->getParameter('ogone.use_aliases')) {
            $transaction->useAlias($alias);
        }

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

    public function renderTemplateAction($twigPath)
    {
        return $this->render(
            $twigPath
        );
    }

    /**
    * Demo for add an alias through a batch action
    */
    public function batchAliasAction()
    {
        $client = OgoneClientQuery::create()
               ->filterByEmail('test@test.com')
               ->findOneOrCreate();

        $client->setFirstname('John');
        $client->setFullname('Doe');
        $client->save();

        $alias = OgoneAliasQuery::create()
                       ->filterByOgoneClient($client)
                       ->filterByOperation(OgoneAliasPeer::OPERATION_BYMERCHANT)
                       ->filterByName('ABONNEMENT')
                       ->findOneOrCreate();

        $alias->save();

        // Method 1 : Without verifications
        /*
        try {
            $response = $this->get('ogone.batch_alias_manager')
                ->addAlias(
                    $alias->getUuid(),
                    $client->getFirstname().' '.$client->getFullName(),
                    '4111111111111111',
                    '1112',
                    'VISA'
                );

            return new Response($response->getContent(), 200, array('content-type' => 'text/xml'));

        } catch (\Cedriclombardot\OgonePaymentBundle\Exception\InvalidBatchDatasException $e) {
            $xml = '<ERRORS>';

            foreach ($e->getErrors() as $error) {
                $xml .= '<ERROR>'.$error->asXml().'</ERROR>';
            }

            $xml .= '</ERRORS>';

            return new Response($xml, 400, array('content-type' => 'text/xml'));

        }
        */

        // Method 2 : By a 0 € authorisation : need ogone activate this feature
        try {
            $authorisation = $this->get('ogone.batch_transaction_manager')
                ->requestAuthorisation(
                    15,
                    '', // No brand specified
                    '4111111111111111',
                    '1112',
                    $client->getFirstname().' '.$client->getFullName(),
                    '123',
                    null, // No order id
                    $alias->getUuid()
                );

            return new Response($authorisation->getContent(), 200, array('content-type' => 'text/xml'));

        } catch (\Cedriclombardot\OgonePaymentBundle\Exception\InvalidBatchDatasException $e) {
            $xml = '<ERRORS>';

            foreach ($e->getErrors() as $error) {
                $xml .= '<ERROR>'.$error->asXml().'</ERROR>';
            }

            $xml .= '</ERRORS>';

            return new Response($xml, 400, array('content-type' => 'text/xml'));

        }

    }
}
