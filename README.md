# Ogone payment bundle, help you to make payment transactions with ogone and Symfony2 ![project status](http://stillmaintained.com/cedriclombardot/OgonePaymentBundle.png)# ![build status](https://secure.travis-ci.org/cedriclombardot/OgonePaymentBundle.png)#

## Features

* Full featured sample controller
* Simple transactions
* Feedback managment
* Alias managment

## Comming

* Batch operations

## Setup

Add in your composer.json :

```
"require": {
   "cedriclombardot/ogone-payment-bundle": "dev-master"
}
```

Configure your kernel

``` php
$bundles = array(
    new Cedriclombardot\OgonePaymentBundle\CedriclombardotOgonePaymentBundle(),
);
```

Configure ogone in config.yml

``` yaml
cedriclombardot_ogone_payment:
    secret:
        shaInKey: Mysecretsig1875!?
        shaOutKey: Mysecretsig1875!?
        algorithm: sha512
    general:
        PSPID: MyCompagny
        currency: EUR
        language: en_EN
    design:
        title: Give Me Your money - Payment page
        bgColor: "#4e84c4"
        txtColor: "#FFFFFF"
        tblBgColor: "#FFFFFF"
        buttonBgColor: "#00467F"
        buttonTxtColor: "#FFFFFF"
        fontType: "Verdana"
```


## Creation of a transaction

In a controller

``` php
<?php

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

```


And the feedback :

``` php
<?php
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

```

## Alias managment

You have Ogone premium account with alias option :

Edit config.yml

``` yaml
cedriclombardot_ogone_payment:
    general:
        use_aliases: true
```
In your transaction controller

``` php
// Client recuperation HERE

// Alias creation
if ($this->container->getParameter('ogone.use_aliases')) {
    $alias = OgoneAliasQuery::create()
               ->filterByOgoneClient($client)
               ->filterByOperation(OgoneAliasPeer::OPERATION_BYMERCHANT)
               ->filterByName('ABONNEMENT')
               ->findOneOrCreate();

   $alias->setLabel('Your abonnement');
   $alias->save();
}

// Transaction creation HERE

if ($this->container->getParameter('ogone.use_aliases')) {
    $transaction->useAlias($alias);
}

$form = $transaction->getForm();

// call the view
```

See a complete controller implementation here [https://github.com/cedriclombardot/OgonePaymentBundle/blob/master/Controller/PaymentController.php](https://github.com/cedriclombardot/OgonePaymentBundle/blob/master/Controller/PaymentController.php)

