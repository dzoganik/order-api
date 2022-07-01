<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Order
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\UniqueConstraint(name: 'uc_order_partner', columns: ['partner_id', 'order_id'])]
class Order
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 100)]
    private string $partnerId;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $order_id;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $deliveryDate;

    /**
     * @var float
     */
    #[ORM\Column(type: 'decimal', precision: 11, scale: 4)]
    private float $orderValue;

    /**
     * @var ArrayCollection|Collection
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: OrderItem::class)]
    private Collection|ArrayCollection $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPartnerId(): string
    {
        return $this->partnerId;
    }

    /**
     * @param string $partnerId
     * @return $this
     */
    public function setPartnerId(string $partnerId): self
    {
        $this->partnerId = $partnerId;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->order_id;
    }

    /**
     * @param string $order_id
     * @return $this
     */
    public function setOrderId(string $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDeliveryDate(): DateTimeInterface
    {
        return $this->deliveryDate;
    }

    /**
     * @param DateTimeInterface $deliveryDate
     * @return $this
     */
    public function setDeliveryDate(DateTimeInterface $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * @return float
     */
    public function getOrderValue(): float
    {
        return $this->orderValue;
    }

    /**
     * @param float $orderValue
     * @return $this
     */
    public function setOrderValue(float $orderValue): self
    {
        $this->orderValue = $orderValue;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    /**
     * @param OrderItem $orderItem
     * @return $this
     */
    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setParent($this);
        }

        return $this;
    }

    /**
     * @param OrderItem $orderItem
     * @return $this
     */
    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getParent() === $this) {
                $orderItem->setParent(null);
            }
        }

        return $this;
    }
}
