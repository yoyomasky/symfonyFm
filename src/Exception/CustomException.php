<?php
/**
 * @author: sma01
 * @since: 2024/6/19
 * @version: 1.0
 */

namespace App\Exception;

class CustomException extends \RuntimeException implements \Throwable
{
    protected $code;
    protected $message;
    protected mixed $data;

    public function __construct(int $code, string $message = '', $data = [], \Throwable $previous = null)
    {
        $this->code    = $code;
        $this->message = $message;
        $this->data    = $data;
        parent::__construct($this->message, $this->code, $previous);
    }

    public function getData()
    {
        return $this->data;
    }
}