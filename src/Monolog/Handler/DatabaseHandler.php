<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Monolog\Handler;

use App\Entity\Logging\Logging;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Psr\Log\LoggerInterface;

class DatabaseHandler extends AbstractProcessingHandler
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $fileLogger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $fileLogger)
    {
        // 不要修改Level,因为如果是DEBUG,框架的日志时,$entityManager还没被初始化
        parent::__construct(Level::Error);
        $this->entityManager = $entityManager;
        $this->fileLogger    = $fileLogger;
    }

    protected function write(LogRecord $record): void
    {
        try {
            $extra   = $record->extra;
            $traceId = $record->extra['trace_id'];
            unset($extra['trace_id']);
            $row = new Logging();
            $row->setChannel($record->channel);
            $row->setLevel($record->level->getName());
            $row->setMessage($record->message);
            $row->setContext($record->context);
            $row->setExtra($extra);
            $row->setTraceId($traceId);
            $row->setCreatedAt(new \DateTime());
            $this->entityManager->persist($row);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->fileLogger->error($exception->getMessage(), ['record' => json_encode($record)]);
        }
    }
}