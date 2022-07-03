<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/orders', name: 'app_order_')]
class OrderController extends CommonController

{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Exception
     */
    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->validateContentType($request->headers->get('content_type'));
        $this->validateRequestData($request->getContent(), Order::class);

        $entityManager->persist($this->data);

        try {
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());

            return $this->json(
                ["error" => 'There was an error while creating the order.'],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }


        return $this->json('created', JsonResponse::HTTP_OK, ["Content-Type" => "application/json"]);
    }
}
