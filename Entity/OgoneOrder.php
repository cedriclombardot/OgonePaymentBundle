<?php

namespace Pilot\OgonePaymentBundle\Entity;

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
     * @var OgoneClient
     *
     * @ORM\ManyToOne(targetEntity="OgoneClient", inversedBy="orders")
     */
    private $client;

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

    protected $onEnd;


    public function onEnd($object)
    {
        $this->onEnd = $object;

        return $this;
    }

    public function end()
    {
        $toReturn = $this->onEnd;
        $this->onEnd = null;

        return $toReturn;
    }

    public function toOgone()
    {
        $convertion = array(
           'orderId'    => 'Id',
           'amount'     => 'Amount',
           'amountHtva' => 'AmountHtva',
           'UserId'     => 'Client',
           'Com'        => 'Description',
           'Operation'  => 'Operation',
           'PM'         => 'PaymentMethod',
           'ECOM_SHIPTO_TELECOM_PHONE_NUMBER' => 'ShiptoPhoneNumber',
           'ECOM_SHIPTO_ONLINE_EMAIL'       => 'ShiptoEmail',
           'ECOM_SHIPTO_POSTAL_NAME_PREFIX' => 'ShiptoCivility',
           'ECOM_SHIPTO_POSTAL_NAME_FIRST'  => 'ShiptoFirstname',
           'ECOM_SHIPTO_POSTAL_NAME_LAST'   => 'ShiptoName',
           'ECOM_SHIPTO_COMPANY'            => 'ShiptoCompagny',
           'ECOM_SHIPTO_POSTAL_STREET_LINE1'=> 'ShiptoAddress',
           'ECOM_SHIPTO_POSTAL_STREET_LINE2'=> 'ShiptoAddress2',
           'ECOM_SHIPTO_POSTAL_CITY'        => 'ShiptoCity',
           'ECOM_SHIPTO_POSTAL_COUNTRYCODE' => 'ShiptoCountrycode',
           'ECOM_SHIPTO_POSTAL_POSTALCODE'  => 'ShiptoPostalcode',
           'ECOM_BILLTO_POSTAL_STREET_LINE1'=> 'BilltoAddress',
           'ECOM_BILLTO_POSTAL_STREET_LINE2'=> 'BilltoAddress2',
           'ECOM_BILLTO_POSTAL_CITY'        => 'BilltoCity',
           'ECOM_BILLTO_POSTAL_COUNTRYCODE' => 'BilltoCountrycode',
           'ECOM_BILLTO_POSTAL_POSTALCODE'  => 'BilltoPostalcode',
        );

        foreach ($convertion as $ogoneKey => $propelGetter) {
            if ('Client' == $propelGetter) {
                $convertion[$ogoneKey] = $this->getClient()->getId();
            } else {
                $convertion[$ogoneKey] = $this->{'get'.$propelGetter}();
            }
        }

        if ($this->getClient()) {
            $convertion = array_merge($convertion, $this->getClient()->toOgone());
        }

        return $convertion;
    }

        /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return OgoneOrder
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amountHtva
     *
     * @param integer $amountHtva
     * @return OgoneOrder
     */
    public function setAmountHtva($amountHtva)
    {
        $this->amountHtva = $amountHtva;

        return $this;
    }

    /**
     * Get amountHtva
     *
     * @return integer
     */
    public function getAmountHtva()
    {
        return $this->amountHtva;
    }

    /**
     * Set amountTva
     *
     * @param integer $amountTva
     * @return OgoneOrder
     */
    public function setAmountTva($amountTva)
    {
        $this->amountTva = $amountTva;

        return $this;
    }

    /**
     * Get amountTva
     *
     * @return integer
     */
    public function getAmountTva()
    {
        return $this->amountTva;
    }

    /**
     * Set OgoneClient
     *
     * @param OgoneClient $client
     * @return OgoneOrder
     */
    public function setClient(OgoneClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get OgoneClient
     *
     * @return OgoneClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return OgoneOrder
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set operation
     *
     * @param boolean $operation
     * @return OgoneOrder
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return boolean
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set shiptoPhoneNumber
     *
     * @param string $shiptoPhoneNumber
     * @return OgoneOrder
     */
    public function setShiptoPhoneNumber($shiptoPhoneNumber)
    {
        $this->shiptoPhoneNumber = $shiptoPhoneNumber;

        return $this;
    }

    /**
     * Get shiptoPhoneNumber
     *
     * @return string
     */
    public function getShiptoPhoneNumber()
    {
        return $this->shiptoPhoneNumber;
    }

    /**
     * Set shiptoEmail
     *
     * @param string $shiptoEmail
     * @return OgoneOrder
     */
    public function setShiptoEmail($shiptoEmail)
    {
        $this->shiptoEmail = $shiptoEmail;

        return $this;
    }

    /**
     * Get shiptoEmail
     *
     * @return string
     */
    public function getShiptoEmail()
    {
        return $this->shiptoEmail;
    }

    /**
     * Set shiptoCivility
     *
     * @param string $shiptoCivility
     * @return OgoneOrder
     */
    public function setShiptoCivility($shiptoCivility)
    {
        $this->shiptoCivility = $shiptoCivility;

        return $this;
    }

    /**
     * Get shiptoCivility
     *
     * @return string
     */
    public function getShiptoCivility()
    {
        return $this->shiptoCivility;
    }

    /**
     * Set shiptoFirstname
     *
     * @param string $shiptoFirstname
     * @return OgoneOrder
     */
    public function setShiptoFirstname($shiptoFirstname)
    {
        $this->shiptoFirstname = $shiptoFirstname;

        return $this;
    }

    /**
     * Get shiptoFirstname
     *
     * @return string
     */
    public function getShiptoFirstname()
    {
        return $this->shiptoFirstname;
    }

    /**
     * Set shiptoName
     *
     * @param string $shiptoName
     * @return OgoneOrder
     */
    public function setShiptoName($shiptoName)
    {
        $this->shiptoName = $shiptoName;

        return $this;
    }

    /**
     * Get shiptoName
     *
     * @return string
     */
    public function getShiptoName()
    {
        return $this->shiptoName;
    }

    /**
     * Set shiptoCompagny
     *
     * @param string $shiptoCompagny
     * @return OgoneOrder
     */
    public function setShiptoCompagny($shiptoCompagny)
    {
        $this->shiptoCompagny = $shiptoCompagny;

        return $this;
    }

    /**
     * Get shiptoCompagny
     *
     * @return string
     */
    public function getShiptoCompagny()
    {
        return $this->shiptoCompagny;
    }

    /**
     * Set shiptoAddress
     *
     * @param string $shiptoAddress
     * @return OgoneOrder
     */
    public function setShiptoAddress($shiptoAddress)
    {
        $this->shiptoAddress = $shiptoAddress;

        return $this;
    }

    /**
     * Get shiptoAddress
     *
     * @return string
     */
    public function getShiptoAddress()
    {
        return $this->shiptoAddress;
    }

    /**
     * Set shiptoAddress2
     *
     * @param string $shiptoAddress2
     * @return OgoneOrder
     */
    public function setShiptoAddress2($shiptoAddress2)
    {
        $this->shiptoAddress2 = $shiptoAddress2;

        return $this;
    }

    /**
     * Get shiptoAddress2
     *
     * @return string
     */
    public function getShiptoAddress2()
    {
        return $this->shiptoAddress2;
    }

    /**
     * Set shiptoCity
     *
     * @param string $shiptoCity
     * @return OgoneOrder
     */
    public function setShiptoCity($shiptoCity)
    {
        $this->shiptoCity = $shiptoCity;

        return $this;
    }

    /**
     * Get shiptoCity
     *
     * @return string
     */
    public function getShiptoCity()
    {
        return $this->shiptoCity;
    }

    /**
     * Set shiptoCountrycode
     *
     * @param string $shiptoCountrycode
     * @return OgoneOrder
     */
    public function setShiptoCountrycode($shiptoCountrycode)
    {
        $this->shiptoCountrycode = $shiptoCountrycode;

        return $this;
    }

    /**
     * Get shiptoCountrycode
     *
     * @return string
     */
    public function getShiptoCountrycode()
    {
        return $this->shiptoCountrycode;
    }

    /**
     * Set shiptoPostalcode
     *
     * @param string $shiptoPostalcode
     * @return OgoneOrder
     */
    public function setShiptoPostalcode($shiptoPostalcode)
    {
        $this->shiptoPostalcode = $shiptoPostalcode;

        return $this;
    }

    /**
     * Get shiptoPostalcode
     *
     * @return string
     */
    public function getShiptoPostalcode()
    {
        return $this->shiptoPostalcode;
    }

    /**
     * Set billtoAddress
     *
     * @param string $billtoAddress
     * @return OgoneOrder
     */
    public function setBilltoAddress($billtoAddress)
    {
        $this->billtoAddress = $billtoAddress;

        return $this;
    }

    /**
     * Get billtoAddress
     *
     * @return string
     */
    public function getBilltoAddress()
    {
        return $this->billtoAddress;
    }

    /**
     * Set billtoAddress2
     *
     * @param string $billtoAddress2
     * @return OgoneOrder
     */
    public function setBilltoAddress2($billtoAddress2)
    {
        $this->billtoAddress2 = $billtoAddress2;

        return $this;
    }

    /**
     * Get billtoAddress2
     *
     * @return string
     */
    public function getBilltoAddress2()
    {
        return $this->billtoAddress2;
    }

    /**
     * Set billtoCity
     *
     * @param string $billtoCity
     * @return OgoneOrder
     */
    public function setBilltoCity($billtoCity)
    {
        $this->billtoCity = $billtoCity;

        return $this;
    }

    /**
     * Get billtoCity
     *
     * @return string
     */
    public function getBilltoCity()
    {
        return $this->billtoCity;
    }

    /**
     * Set billtoCountrycode
     *
     * @param string $billtoCountrycode
     * @return OgoneOrder
     */
    public function setBilltoCountrycode($billtoCountrycode)
    {
        $this->billtoCountrycode = $billtoCountrycode;

        return $this;
    }

    /**
     * Get billtoCountrycode
     *
     * @return string
     */
    public function getBilltoCountrycode()
    {
        return $this->billtoCountrycode;
    }

    /**
     * Set billtoPostalcode
     *
     * @param string $billtoPostalcode
     * @return OgoneOrder
     */
    public function setBilltoPostalcode($billtoPostalcode)
    {
        $this->billtoPostalcode = $billtoPostalcode;

        return $this;
    }

    /**
     * Get billtoPostalcode
     *
     * @return string
     */
    public function getBilltoPostalcode()
    {
        return $this->billtoPostalcode;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return OgoneOrder
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set acceptance
     *
     * @param string $acceptance
     * @return OgoneOrder
     */
    public function setAcceptance($acceptance)
    {
        $this->acceptance = $acceptance;

        return $this;
    }

    /**
     * Get acceptance
     *
     * @return string
     */
    public function getAcceptance()
    {
        return $this->acceptance;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return OgoneOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set cardNumber
     *
     * @param string $cardNumber
     * @return OgoneOrder
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set payid
     *
     * @param string $payid
     * @return OgoneOrder
     */
    public function setPayid($payid)
    {
        $this->payid = $payid;

        return $this;
    }

    /**
     * Get payid
     *
     * @return string
     */
    public function getPayid()
    {
        return $this->payid;
    }

    /**
     * Set ncError
     *
     * @param string $ncError
     * @return OgoneOrder
     */
    public function setNcError($ncError)
    {
        $this->ncError = $ncError;

        return $this;
    }

    /**
     * Get ncError
     *
     * @return string
     */
    public function getNcError()
    {
        return $this->ncError;
    }

    /**
     * Set brand
     *
     * @param string $brand
     * @return OgoneOrder
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return OgoneOrder
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set ed
     *
     * @param string $ed
     * @return OgoneOrder
     */
    public function setEd($ed)
    {
        $this->ed = $ed;

        return $this;
    }

    /**
     * Get ed
     *
     * @return string
     */
    public function getEd()
    {
        return $this->ed;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     * @return OgoneOrder
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set transactionDate
     *
     * @param \DateTime $transactionDate
     * @return OgoneOrder
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * Get transactionDate
     *
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Set paymentMethod
     *
     * @param boolean $paymentMethod
     * @return OgoneOrder
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return boolean
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return OgoneOrder
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return OgoneOrder
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
