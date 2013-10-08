<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OgoneOrder
 *
 * @ORM\Table(name="ogone_order")
 * @ORM\Entity
 */
class OgoneOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_htva", type="integer", nullable=true)
     */
    private $amountHtva;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_tva", type="integer", nullable=true)
     */
    private $amountTva;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="integer", nullable=true)
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="operation", type="boolean", nullable=true)
     */
    private $operation;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_phone_number", type="string", length=30, nullable=true)
     */
    private $shiptoPhoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_email", type="string", length=255, nullable=true)
     */
    private $shiptoEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_civility", type="string", length=10, nullable=true)
     */
    private $shiptoCivility;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_firstname", type="string", length=255, nullable=true)
     */
    private $shiptoFirstname;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_name", type="string", length=255, nullable=true)
     */
    private $shiptoName;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_compagny", type="string", length=255, nullable=true)
     */
    private $shiptoCompagny;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_address", type="text", nullable=true)
     */
    private $shiptoAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_address2", type="text", nullable=true)
     */
    private $shiptoAddress2;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_city", type="string", length=55, nullable=true)
     */
    private $shiptoCity;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_countrycode", type="string", length=55, nullable=true)
     */
    private $shiptoCountrycode;

    /**
     * @var string
     *
     * @ORM\Column(name="shipto_postalcode", type="string", length=55, nullable=true)
     */
    private $shiptoPostalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="billto_address", type="text", nullable=true)
     */
    private $billtoAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="billto_address2", type="text", nullable=true)
     */
    private $billtoAddress2;

    /**
     * @var string
     *
     * @ORM\Column(name="billto_city", type="string", length=55, nullable=true)
     */
    private $billtoCity;

    /**
     * @var string
     *
     * @ORM\Column(name="billto_countrycode", type="string", length=55, nullable=true)
     */
    private $billtoCountrycode;

    /**
     * @var string
     *
     * @ORM\Column(name="billto_postalcode", type="string", length=55, nullable=true)
     */
    private $billtoPostalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=5, nullable=true)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="acceptance", type="string", length=255, nullable=true)
     */
    private $acceptance;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="card_number", type="string", length=255, nullable=true)
     */
    private $cardNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="payid", type="string", length=255, nullable=true)
     */
    private $payid;

    /**
     * @var string
     *
     * @ORM\Column(name="nc_error", type="string", length=255, nullable=true)
     */
    private $ncError;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255, nullable=true)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="ed", type="string", length=255, nullable=true)
     */
    private $ed;

    /**
     * @var string
     *
     * @ORM\Column(name="client_name", type="string", length=555, nullable=true)
     */
    private $clientName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="transaction_date", type="date", nullable=true)
     */
    private $transactionDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="payment_method", type="boolean", nullable=true)
     */
    private $paymentMethod;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


}