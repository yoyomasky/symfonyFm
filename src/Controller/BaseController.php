<?php
/**
 * @author: sma01
 * @since: 2024/6/20
 * @version: 1.0
 */

namespace App\Controller;

use App\Const\ResponseMessage;
use App\Exception\CustomException;
use App\Service\ExceptionService;
use App\Service\ResponseService;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerInterface;
use Throwable;

class BaseController extends AbstractController
{
    protected ResponseService $responseService;
    protected SerializerInterface $serializer;
    protected ExceptionService $exceptionService;

    public function __construct(
        ResponseService     $responseService,
        SerializerInterface $serializer,
        ExceptionService    $exceptionService,
    )
    {
        $this->responseService  = $responseService;
        $this->serializer       = $serializer;
        $this->exceptionService = $exceptionService;
    }

    public function serializer(mixed $data, string $group)
    {
        try {
            $context = SerializationContext::create()->setGroups($group)->setSerializeNull(true);
            $data    = $this->serializer->serialize($data, 'json', $context);
            return json_decode($data, true);
        } catch (\Exception $exception) {
            throw new CustomException(
                ResponseMessage::SYSTEM_SERIALIZE_ERROR,
                '',
                [],
                $exception
            );
        }
    }

    public function setResponse(mixed $data, int $code = 200, string $message = '', array $headers = []): JsonResponse
    {
        return $this->responseService->json($data, $code, $message, $headers);
    }

    public function setException(Throwable $throwable, bool $handle = false): JsonResponse
    {
        if ($handle) {
            $this->exceptionService->handle($throwable);
        }
        return $this->responseService->exception($throwable);
    }
}