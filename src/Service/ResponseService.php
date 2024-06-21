<?php
/**
 * @author: sma01
 * @since: 2024/6/19
 * @version: 1.0
 */

namespace App\Service;

use App\Const\ResponseMessage;
use App\Util\Util;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResponseService
{
    public int $code;

    public string $message;

    public mixed $data;
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function json(mixed $data, int $code = 200, string $message = '', array $headers = []): JsonResponse
    {
        $statusCode = 200;
        if (array_key_exists($code, Response::$statusTexts)) {
            $statusCode = $code;
        }
        if (!$message && array_key_exists($code, ResponseMessage::$codeMessage)) {
            $message = ResponseMessage::$codeMessage[$code];
        }
        $traceId = $this->requestStack->getCurrentRequest()->attributes->get('trace_id');
        $rs      = [
            'code'     => $this->checkCode($code),
            'message'  => $message,
            'data'     => $data ?? [],
            'trace_id' => $traceId
        ];
        return new JsonResponse($rs, $statusCode, $headers);
    }

    public function exception(\Throwable $throwable): JsonResponse
    {
        $data = [];
        if ($throwable instanceof HttpException) {
            $code = $throwable->getStatusCode();
        } else {
            $code = $throwable->getCode();
        }
        $code = $this->checkCode($code);
        // 使用#[MappingQueryString]时请求参数错误报的错
        if (Util::isDTOError($throwable->getMessage())) {
            $code = ResponseMessage::REQUEST_MAPPING_DTO_INVALID;
        }
        $message = $throwable->getMessage();
        if (method_exists($throwable, 'getData')) {
            $data = $throwable->getData();
            if (!is_array($data)) {
                $data = [];
            }
        }
        return $this->json($data, $code, $message);
    }

    private function checkCode(int $code): int
    {
        if (array_key_exists($code, ResponseMessage::$codeMessage)) {
            return $code;
        } else if (array_key_exists($code, Response::$statusTexts)) {
            return $code;
        }
        return ResponseMessage::UNKNOWN_ERROR;
    }
}