<?php

namespace WebHelpers\WHCallback\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ps_whcallback_request")
 * @ORM\Entity(repositoryClass="WebHelpers\WHCallback\Repository\WHCallbackRequestRepository")
 */
class WHCallbackRequest
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_request", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var datetime
     *
     * @ORM\Column(name="request_date_add", type="datetime")
     */
    private $requestDateAdd;

    public function __construct()
    {
        $this->requestDateAdd = new DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return WHCallbackRequest
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     *
     * @return WHCallbackRequest
     */
    public function setLastname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->firstname;
    }

    /**
     * @param string $lastname
     *
     * @return WHCallbackRequest
     */
    public function setFirstname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return datetime
     */
    public function getRequestDateAdd()
    {
        return $this->requestDateAdd;
    }
}
