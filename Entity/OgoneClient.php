<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OgoneClient
 *
 * @ORM\Table(name="ogone_client")
 * @ORM\Entity
 */
class OgoneClient
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
     * @var boolean
     *
     * @ORM\Column(name="gender", type="boolean", nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="civility", type="string", length=10, nullable=true)
     */
    private $civility;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255, nullable=true)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=25, nullable=true)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="text", nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=55, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="town", type="string", length=40, nullable=true)
     */
    private $town;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=30, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number2", type="string", length=30, nullable=true)
     */
    private $phoneNumber2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

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
