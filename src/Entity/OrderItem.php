<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class OrderItem
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
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
    private string $productId;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    /**
     * @var float
     */
    #[ORM\Column(type: 'decimal', precision: 11, scale: 4)]
    private float $price;

    /**
     * @var float
     */
    #[ORM\Column(type: 'decimal', precision: 10, scale: 4)]
    private float $quantity;

    /**
     * @var Order
     */
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'parent')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Order $parent;

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
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @param string $productId
     * @return $this
     */
    public function setProductId(string $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     * @return $this
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Order
     */
    public function getParent(): Order
    {
        return $this->parent;
    }

    /**
     * @param Order|null $parent
     * @return $this
     */
    public function setParent(?Order $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
