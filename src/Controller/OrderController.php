<?php

declare(strict_types=1);

namespace App\Controller;

use App\Result\OrderResult;
use App\Result\ResultInterface;
use App\Service\RequestToOrderConvertor;
use App\Validator\RequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/orders', name: 'app_order_')]
class OrderController extends AbstractController
{
    public const CONTENT_TYPE = 'application/json';
    public const RESPONSE_FORMAT = 'json';

    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param LoggerInterface $logger
     * @param RequestValidator $requestValidator
     * @param RequestToOrderConvertor $requestToOrderConvertor
     */
    public function __construct(
        protected SerializerInterface $serializer,
        protected ValidatorInterface $validator,
        protected LoggerInterface $logger,
        protected RequestValidator $requestValidator,
        protected RequestToOrderConvertor $requestToOrderConvertor
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
        $this->requestValidator->validateContentType($request->headers->get('content_type'));

        $order = $this->requestToOrderConvertor->createOrderFromRequest($request->getContent());
        $this->requestValidator->validateOrder($order);

        $entityManager->persist($order);
        $entityManager->flush();

        $result = new OrderResult();
        $result->setOrderId($order->getOrderId());
        $result->setPartnerId($order->getPartnerId());

        return $this->createResponse($result, Response::HTTP_CREATED);
    }

    /**
     * @param string $orderId
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws Exception
     */
    #[Route('/{orderId}', name: 'update', methods: 'PATCH')]
    public function update(
        string $orderId,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $partnerId = $request->headers->get('partner_id');
        $this->requestValidator->validatePartnerId($partnerId);
        $this->requestValidator->validateContentType($request->headers->get('content_type'));

        $order = $this->requestToOrderConvertor->updateOrderFromRequest($orderId, $partnerId, $request->getContent());

        $entityManager->persist($order);
        $entityManager->flush();

        $result = new OrderResult();
        $result->setOrderId($order->getOrderId());
        $result->setPartnerId($order->getPartnerId());
        $result->setDeliveryDate($order->getDeliveryDate()->format('Y-m-d'));

        return $this->createResponse($result, Response::HTTP_OK);
    }

    /**
     * @param ResultInterface|null $content
     * @param int $status
     * @return Response
     */
    protected function createResponse(ResultInterface $content = null, int $status = Response::HTTP_OK)
    {
        $content = $this->serializer->serialize($content, self::RESPONSE_FORMAT);

        return new Response($content, $status, ['Content-Type' => self::CONTENT_TYPE]);
    }
}
