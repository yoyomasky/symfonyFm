<?php
/**
 * @author: sma01
 * @since: 2024/6/19
 * @version: 1.0
 */

namespace App\Exception;

use App\Const\ResponseMessage;

class SqlException extends \RuntimeException
{
    private string $sql;

    private array $data;

    public function __construct(string $message = "", mixed $data = null, string $sql = '')
    {
        parent::__construct($message, ResponseMessage::SQL_ERROR);
        $this->data = json_decode(json_encode($data), true);
        $this->sql  = $sql;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getSql(): string
    {
        return $this->sql;
    }
}