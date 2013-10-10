<?php

namespace Pilot\OgonePaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OgoneAlias
 *
 * @ORM\Table(name="ogone_alias")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class OgoneAlias
{
    /** enumerate fields */
    const OPERATION = 'operation';
    const STATUS = 'status';

    /** The enumerated values for the operation field */
    const OPERATION_BYMERCHANT = 'BYMERCHANT';
    const OPERATION_BYPSP = 'BYPSP';

    /** The enumerated values for the status field */
    const STATUS_PENDING = 'PENDING';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_ERROR = 'ERROR';

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        self::OPERATION => array(
            self::OPERATION_BYMERCHANT,
            self::OPERATION_BYPSP,
        ),
        self::STATUS => array(
            self::STATUS_PENDING,
            self::STATUS_ACTIVE,
            self::STATUS_ERROR,
        ),
    );

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var OgoneClient
     *
     * @ORM\OneToOne(targetEntity="OgoneClient", inversedBy="alias")
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=255, nullable=false)
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="operation", type="boolean", nullable=false)
     */
    private $operation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="fileId", type="string", length=255, nullable=true)
     */
    private $fileid;

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


    /** @ORM\PrePersist */
    public function onPrePersist()
    {
        $this->setUuid(uniqid('sf_'));
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
     * Set uuid
     *
     * @param string $uuid
     * @return OgoneAlias
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return OgoneAlias
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set operation
     *
     * @param boolean $operation
     * @return OgoneAlias
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
     * Set status
     *
     * @param boolean $status
     * @return OgoneAlias
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set fileid
     *
     * @param string $fileid
     * @return OgoneAlias
     */
    public function setFileid($fileid)
    {
        $this->fileid = $fileid;

        return $this;
    }

    /**
     * Get fileid
     *
     * @return string
     */
    public function getFileid()
    {
        return $this->fileid;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return OgoneAlias
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
     * @return OgoneAlias
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

    public function toOgone()
    {
        $convertion = array(
           'AliasOperation' => 'Operation',
           'Alias'          => 'Uuid',
           'AliasUsage'     => 'Name'
        );

        foreach ($convertion as $ogoneKey => $propelGetter) {
            $convertion[$ogoneKey] = $this->{'get'.$propelGetter}();
        }

        return $convertion;
    }

    /**
     * Gets the list of values for all ENUM columns
     *
     * @return array
     */
    public static function getValueSets()
    {
      return self::$enumValueSets;
    }
}
