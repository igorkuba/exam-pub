<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DrinkOrderRepository")
 */
class DrinkOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="drinkOrder", orphanRemoval=true, cascade={"persist"})
     */
    private $orderItems;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="drinkOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->date=new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setDrinkOrder($this);
            $this->customer->decreaseWallet($orderItem->getPrice());
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->contains($orderItem)) {
            $this->orderItems->removeElement($orderItem);
            $this->customer->increaseWallet($orderItem->getPrice());
            // set the owning side to null (unless already changed)
            if ($orderItem->getDrinkOrder() === $this) {
                $orderItem->setDrinkOrder(null);
            }
        }

        return $this;
    }
    
    public function getSum(): int
    {
        $sum=0;
        foreach ($this->orderItems as $item)
            $sum+=$item->getPrice();
        return $sum;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
