<?php

declare(strict_types=1);

namespace App\Event\Listener;

use App\Controller\CommonController;
use App\Exception\ApplicationException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __construct(
        protected LoggerInterface $logger
    ) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof ApplicationException) {
            $response = $this->handleKnownExceptions($event->getThrowable());
        } else {
            $response = $this->handleUnknownExceptions($event->getThrowable());
        }

        $event->setResponse($response);
    }

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

    private function handleUnknownExceptions(Exception $exception): Response
    {
        $this->logger->error($exception);

        return new Response('An unknown exception occurred.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
