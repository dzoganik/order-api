<?php

declare(strict_types=1);

namespace App\Event\Listener;

use App\Controller\CommonController;
use App\Exception\ApplicationException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 * @package App\Event\Listener
 */
class ExceptionListener
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected LoggerInterface $logger
    ) {}

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof ApplicationException) {
            $response = $this->handleKnownExceptions($event->getThrowable());
        } else {
            $response = $this->handleUnknownExceptions($event->getThrowable());
        }

        $event->setResponse($response);
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    private function handleKnownExceptions(Exception $exception): Response
    {
        $header = [];
        if (Response::HTTP_BAD_REQUEST === $exception->getStatusCode()) {
            $header = ['Content-Type' => CommonController::CONTENT_TYPE];
        } else {
            $this->logger->error($exception);
        }

        return new Response($exception->getMessage(), $exception->getStatusCode(), $header);
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    private function handleUnknownExceptions(Exception $exception): Response
    {
        $this->logger->error($exception);

        return new Response('An unknown exception occurred.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
