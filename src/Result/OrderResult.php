<?php

declare(strict_types=1);

namespace App\Result;

use DateTimeInterface;

/**
 * Class OrderResult
 * @package App\Result
 */
class OrderResult implements ResultInterface
{
    /**
     * @var string
     */
    private string $orderId;

    /**
     * @var string
     */
    private string $partnerId;

    /**
     * @var string
     */
    private string $deliveryDate;

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return $this
     */
    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
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
    public function getDeliveryDate(): string
    {
        return $this->deliveryDate;
    }

    /**
     * @param string $deliveryDate
     * @return $this
     */
    public function setDeliveryDate(string $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;
        return $this;
    }
}
