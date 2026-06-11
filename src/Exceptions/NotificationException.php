<?php

namespace Pippa\NotificationSdkCurl\Exceptions;

use Exception;

class NotificationException extends Exception
{
    protected array $errors;

    public function __construct(
        string $message,
        int $code = 0,
        array|string $errors = [],
        ?\Throwable $previous = null
    ) {
        if (is_string($errors)) {
            $this->errors = !empty($errors) ? [$errors] : [];
        } else {
            $this->errors = $errors;
        }
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
