<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Result\OrderResult;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/orders', name: 'app_order_')]
class OrderController extends CommonController
{
    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected SerializerInterface $serializer,
        protected ValidatorInterface $validator,
        protected LoggerInterface $logger
    ) {}

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->validateContentType($request->headers->get('content_type'));
        $this->validateRequestCreateData($request->getContent(), Order::class);

        $entityManager->persist($this->data);
        $entityManager->flush();

        $result = new OrderResult();
        $result->setOrderId($this->data->getOrderId());
        $result->setPartnerId($this->data->getPartnerId());

        return $this->createResponse($result, Response::HTTP_CREATED);
    }

    #[Route('/{orderId}', name: 'update', methods: 'PATCH')]
    public function update(
        string $orderId,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->validatePartnerId($request->headers->get('partner_id'));
        $this->validateContentType($request->headers->get('content_type'));
        $this->validateRequestUpdateData($request, $entityManager);

        $entityManager->persist($this->data);
        $entityManager->flush();

        $result = new OrderResult();
        $result->setOrderId($this->data->getOrderId());
        $result->setPartnerId($this->data->getPartnerId());

        return $this->createResponse($result, Response::HTTP_CREATED);
    }
}
