<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ValidationException;
use App\Model\ResponseInterface;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class CommonController extends AbstractController
{
    public const CONTENT_TYPE = 'application/json';
    private const RESPONSE_FORMAT = 'json';

    protected $data;

    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        protected SerializerInterface $serializer,
        protected ValidatorInterface $validator
    ) {}

    /**
     * @param string $contentType
     * @return void
     */
    protected function validateContentType(string $contentType): void
    {
        if (self::CONTENT_TYPE !== $contentType) {
            throw new ValidationException(
                'Invalid content type header.',
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE
            );
        }
    }

    /**
     * @param string $data
     * @param string $model
     * @return void
     */
    protected function validateRequestData(string $data, string $model): void
    {
        $this->data = $this->serializer->deserialize($data, $model, self::RESPONSE_FORMAT);

        $errors = $this->validator->validate($this->data);
        if ($errors->count() > 0) {
            throw new ValidationException($this->createErrorMessage($errors), Response::HTTP_BAD_REQUEST);
        }
    }

    protected function createResponse(ResponseInterface $content = null, int $status = Response::HTTP_OK)
    {
        $context = new SerializationContext();
        $context->setSerializeNull(false);

        $content = $this->serializer->serialize($content, self::RESPONSE_FORMAT, $context);

        return new Response($content, $status, ['Content-Type' => self::CONTENT_TYPE]);
    }

    private function createErrorMessage(ConstraintViolationListInterface $violations): string
    {
        $errors = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[Inflector::tableize($violation->getPropertyPath())] = $violation->getMessage();
        }

        return json_encode(['errors' => $errors]);
    }
}
