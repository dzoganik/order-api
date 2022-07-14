<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Exception\ValidationException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class RequestToOrderConvertor
 * @package App\Service
 */
class RequestToOrderConvertor
{
    public const RESPONSE_FORMAT = 'json';

    /**
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        protected SerializerInterface $serializer,
        protected EntityManagerInterface $entityManager
    ) {}

    /**
     * @param string $data
     * @return Order
     */
    public function createOrderFromRequest(string $data): Order
    {
        return $this->serializer->deserialize($data, Order::class, self::RESPONSE_FORMAT);
    }

    /**
     * @param string $orderId
     * @param string $partnerId
     * @param string $data
     * @return Order
     * @throws Exception
     */
    public function updateOrderFromRequest(string $orderId, string $partnerId, string $data): Order
    {
        $data = json_decode($data, true);

        if (!$data) {
            throw new ValidationException('Empty request body.', Response::HTTP_BAD_REQUEST);
        }

        $order = $this->getOrder($orderId, $partnerId);

        if (!$order) {
            throw new ValidationException('Order not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->setUpdatedOrder($data, $order);
    }

    /**
     * @param string $orderId
     * @param string $partnerId
     * @return Order|null
     */
    protected function getOrder(string $orderId, string $partnerId): ?Order
    {
        $orderRepo = $this->entityManager->getRepository(Order::class);

        return $orderRepo->findOneBy([
            'orderId' => $orderId,
            'partnerId' => $partnerId,
        ]);
    }

    /**
     * @param array $data
     * @param Order $order
     * @return Order
     * @throws Exception
     */
    protected function setUpdatedOrder(array $data, Order $order): Order
    {
        if (isset($data['deliveryDate'])) {
            if (empty($data['deliveryDate'])) {
                throw new ValidationException('Property deliveryDate cannot be empty.', Response::HTTP_BAD_REQUEST);
            }

            $order->setDeliveryDate(new DateTime($data['deliveryDate']));
        }

        return $order;
    }
}
