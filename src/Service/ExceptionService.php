<?php
/**
 * @author: sma01
 * @since: 2024/6/19
 * @version: 1.0
 */


namespace App\Service;

use App\Exception\CustomException;
use App\Exception\SqlException;
use App\Util\Util;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionService
{
    private \Throwable $throwable;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $exceptionLogger)
    {
        $this->logger = $exceptionLogger;
    }

    public function handle(\Throwable $throwable): static
    {
        try {
            // 异常保存到数据库 channel exception
            $this->throwable = $throwable;
            if (!$this->isHandle($throwable)) {
                return $this;
            }

            $message = mb_substr($throwable->getMessage(), 0, 255);
            $context = [
                'code'    => $throwable->getCode(),
                'message' => $throwable->getMessage(),
            ];
            if ($throwable instanceof CustomException) {
                $context['data'] = $throwable->getData();
                // 真正的错误
                $previous = $throwable->getPrevious();
                if ($previous) {
                    $message = $previous->getMessage();
                }
            }
            if ($throwable instanceof SqlException) {
                $message               = 'sql exception';
                $context['message']    = $throwable->getMessage();
                $context['sql']        = $throwable->getSql();
                $context['parameters'] = $throwable->getData();
            }
            $this->logger->error($message, $context);
            return $this;
        } catch (\Exception $exception) {
            return $this;
        }
    }

    /**
     * 获取异常
     */
    public function getThrowable(): \Throwable
    {
        return $this->throwable;
    }

    private function isHandle(\Throwable $throwable): bool
    {
        // 使用#[MappingQueryString]时请求参数错误报的错
        if (Util::isDTOError($throwable->getMessage())) {
            return false;
        }
        if ($throwable instanceof HttpException) {
            return false;
        }
        return true;
    }
}
