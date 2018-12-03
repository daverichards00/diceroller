<?php

namespace daverichards00\DiceRoller;

use Throwable;

class DiceException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
