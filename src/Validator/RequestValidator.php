<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Order;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestValidator
 * @package App\Validator
 */
class RequestValidator
{
    public const CONTENT_TYPE = 'application/json';

    /**
     * @var Order
     */
    protected Order $order;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(
        protected ValidatorInterface $validator
    ) {}

    /**
     * @param string $contentType
     * @return void
     */
    public function validateContentType(string $contentType): void
    {
        if (self::CONTENT_TYPE !== $contentType) {
            throw new ValidationException(
                'Invalid content type header.',
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE
            );
        }
    }

    /**
     * @param string|null $partnerId
     * @return void
     */
    public function validatePartnerId(?string $partnerId): void
    {
        if (!$partnerId) {
            throw new ValidationException(
                'Missing partner ID.',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @param Order $order
     * @return void
     */
    public function validateOrder(Order $order): void
    {
        $errors = $this->validator->validate($order);
        if ($errors->count() > 0) {
            throw new ValidationException($this->createErrorMessage($errors), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param ConstraintViolationListInterface $violations
     * @return string
     */
    private function createErrorMessage(ConstraintViolationListInterface $violations): string
    {
        $errors = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return json_encode(['errors' => $errors]);
    }
}
