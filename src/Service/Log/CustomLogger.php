<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */

namespace App\Service\Log;

use App\Entity\Logging\Logging;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Exception;

class CustomLogger
{
    const LEVEL_INFO = 'INFO';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_DEBUG = 'DEBUG';

    private string $traceId;
    private string $channel;

    private LoggerInterface $LoggerInterface;
    private EntityManagerInterface $EntityManagerInterface;

    public function __construct(
        LoggerInterface        $LoggerInterface,
        EntityManagerInterface $EntityManagerInterface,
    )
    {
        $this->LoggerInterface        = $LoggerInterface;
        $this->EntityManagerInterface = $EntityManagerInterface;
    }

    public function setLogger(string $channel): void
    {
        $this->channel = $channel;
        if (empty($traceId)) {
            $this->traceId = $this->getTraceId();
        } else {
            $this->traceId = $traceId;
        }
    }

    public function setTraceId(string $traceId): void
    {
        $this->traceId = $traceId;
    }

    public function getTraceId(): string
    {
        if (empty($this->traceId)) {
            $this->traceId = bin2hex(random_bytes(16));
        }
        return $this->traceId;
    }

    private function log(string $level, string $message, array $context = [], array $extra = []): void
    {
        try {
            $Logging = new Logging();
            $Logging->setChannel($this->channel);
            $Logging->setLevel($level);
            $Logging->setMessage($message);
            $Logging->setContext($context);
            $Logging->setExtra($extra);
            $Logging->setTraceId($this->traceId);
            $Logging->setCreatedAt(new \DateTime());

            $this->EntityManagerInterface->persist($Logging);
            $this->EntityManagerInterface->flush();
        } catch (\Exception $exception) {
            $this->LoggerInterface->error($exception->getMessage(), ['message' => $message, 'context' => $context, 'extra' => $extra]);
        }
    }

    public function info(string $message, array $context = [], array $extra = []): void
    {
        $this->log(self::LEVEL_INFO, $message, $context, $extra);
    }

    public function error(string $message, array $context = [], array $extra = []): void
    {
        $this->log(self::LEVEL_ERROR, $message, $context, $extra);
    }

    public function debug(string $message, array $context = [], array $extra = []): void
    {
        $this->log(self::LEVEL_DEBUG, $message, $context, $extra);
    }
}
