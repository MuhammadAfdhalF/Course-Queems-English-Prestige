<?php

namespace App\Exceptions;

use RuntimeException;

class InconsistentEnrollmentStateException extends RuntimeException
{
    public function __construct(string $message = 'Completed enrollment does not have a certificate and requires manual recovery.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
