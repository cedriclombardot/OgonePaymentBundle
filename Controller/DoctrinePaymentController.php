<?php

namespace Cedriclombardot\OgonePaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Cedriclombardot\OgonePaymentBundle\Entity\OgoneClient;
use Cedriclombardot\OgonePaymentBundle\Entity\OgoneAlias;
use Cedriclombardot\OgonePaymentBundle\Entity\OgoneOrder;

class DoctrinePaymentController extends Controller
{
    public function indexAction()
    {
        $client = $this->getRepository('CedriclombardotOgonePaymentBundle:Client')->findOneBy(array(
            'email' => 'test@test.com'
        ));

        if (!$client) {
            $client = new OgoneClient();
            $client->setEmail('test@test.com');
            $this->getManager()->persist();
            $this->getManager()->flush();
        }

        // if ($this->container->getParameter('ogone.use_aliases')) {
        //     $alias = OgoneAliasQuery::create()
        //                ->filterByOgoneClient($client)
        //                ->filterByOperation(OgoneAliasPeer::OPERATION_BYMERCHANT)
        //                ->filterByName('ABONNEMENT')
        //                ->findOneOrCreate();

        //      $alias->save();
        // }

        $transaction = $this->get('ogone.transaction_builder')
            ->order()
                //->setClient($client)
                ->setAmount(0)
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

        if ($this->get('ogone.feedbacker')->hasOrder()) {
            $this->get('ogone.feedbacker')->updateOrder();
        }

        if ($this->get('ogone.feedbacker')->hasAlias()) {
            $this->get('ogone.feedbacker')->updateAlias();
        }

        return $this->render(
            'CedriclombardotOgonePaymentBundle:Payment:feedback.html.twig'
        );
    }

    public function renderTemplateAction($twigPath)
    {
        $context = array();

        if ($this->get('request')->get('context')) {
            $context = json_decode(base64_decode($this->get('request')->get('context')), true);
        }

        return $this->render(
            $twigPath,
            $context
        );
    }

    protected function getRepository($name)
    {
        return $this->getManager()->getRepository($name);
    }

    protected function getManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
