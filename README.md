# Ogone payment bundle, [ogone](http://ogone.com)

Doctrine port of [Pilot/OgonePaymentBundle](https://github.com/Pilot/OgonePaymentBundle)

[build status](https://secure.travis-ci.org/pilot/OgonePaymentBundle.png)

## Features

* Full featured sample controller
* Simple transactions
* Feedback managment
* Alias managment

## Todo

* make part of original bundle with doctrine ORM option

## Setup

Add in your composer.json :

```
"require": {
   "pilot/ogone-payment-bundle": "dev-master"
}
```

Configure your kernel

```php
$bundles = array(
    new Pilot\OgonePaymentBundle\PilotOgonePaymentBundle(),
);
```

Configure ogone in config.yml

```yaml
pilot_ogone_payment:
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

In the controller

```php
    public function indexAction()
    {
        $client = $this->getRepository('PilotOgonePaymentBundle:OgoneClient')->findOneBy(array(
            'email' => 'test@test.com',
        ));

        if (!$client) {
            $client = new OgoneClient();
            $client->setEmail('test@test.com');

            $this->getManager()->persist($client);
            $this->getManager()->flush();
        }

        $transaction = $this->get('ogone.transaction_builder')
            ->order()
                ->setClient($client)
                ->setAmount(99)
            ->end()
            ->configure()
                ->setBgColor('#ffffff')
                ->setAcceptUrl($this->generateUrl('ogone_payment_feedback', array(), true))
                ->setDeclineUrl($this->generateUrl('ogone_payment_feedback', array(), true))
                ->setExceptionUrl($this->generateUrl('ogone_payment_feedback', array(), true))
                ->setCancelUrl($this->generateUrl('ogone_payment_feedback', array(), true))
                ->setBackUrl($this->generateUrl('ogone_payment_feedback', array(), true))
            ->end()
        ;

        $transaction->save();

        $form = $transaction->getForm();

        return $this->render(
            'PilotOgonePaymentBundle:Payment:index.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
```


And the feedback:

```php
<?php
    public function feedbackAction()
    {
        if (!$this->get('ogone.feedbacker')->isValidCall()) {
            throw $this->createNotFoundException();
        }

        $this->get('ogone.feedbacker')->updateOrder();

        return $this->render(
            'PilotOgonePaymentBundle:Payment:feedback.html.twig'
        );
    }

```

## Alias managment

You have Ogone premium account with alias option:

Update `config.yml`

```yaml
pilot_ogone_payment:
    general:
        use_aliases: true
```

In your transaction controller

``` php
// Client recuperation HERE

// Transaction creation HERE

$transaction->save();

if ($this->container->getParameter('ogone.use_aliases')) {
    $alias = $this->getRepository('PilotOgonePaymentBundle:OgoneAlias')->findOneBy(array(
        'client' => $client,
        'operation' => OgoneAlias::OPERATION_BYMERCHANT,
        'name' => 'ABONNEMENT',
    ));

    if (!$alias) {
        $alias = new OgoneAlias();
        $alias
            ->setClient($client)
            ->setOperation(OgoneAlias::OPERATION_BYMERCHANT)
            ->setStatus(OgoneAlias::STATUS_ACTIVE)
            ->setName('ABONNEMENT')
        ;

        $this->getManager()->persist($alias);
        $this->getManager()->flush();
    }

    $transaction->useAlias($alias);
}

$form = $transaction->getForm();

// render the view
```

See a complete controller implementation here [https://github.com/pilot/OgonePaymentBundle/blob/master/Controller/PaymentController.php](https://github.com/pilot/OgonePaymentBundle/blob/master/Controller/PaymentController.php)

