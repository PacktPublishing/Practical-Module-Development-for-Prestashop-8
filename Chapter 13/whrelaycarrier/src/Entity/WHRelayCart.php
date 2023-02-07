<?php

namespace WebHelpers\WHRelayCarrier\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ps_whrelaycarrier_relay_cart")
 * @ORM\Entity(repositoryClass="WebHelpers\WHRelayCarrier\Repository\WHRelayCartRepository")
 */
class WHRelayCart
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_relay_cart", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_relay", type="integer")
     */
    private $relay;

    /**
     * @var int
     *
     * @ORM\Column(name="id_cart", type="integer")
     */
    private $cart;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRelay()
    {
        return $this->relay;
    }

    /**
     * @param int $relay
     *
     * @return WHRelayCart
     */
    public function setRelay($relay)
    {
        $this->relay = $relay;
        return $this;
    }

    /**
     * @return int
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param int $cart
     *
     * @return WHRelayCart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
        return $this;
    }
}
