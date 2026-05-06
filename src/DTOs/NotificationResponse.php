<?php

namespace Pippa\NotificationSdkCurl\DTOs;

class NotificationResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly int $statusCode,
        public readonly string $message,
        public readonly mixed $data,
        public readonly array $raw = [],
    ) {}

    public static function fromArray(array $body, int $statusCode): static
    {
        return new static(
            success:    ($body['success'] ?? false) === true,
            statusCode: $statusCode,
            message:    $body['message'] ?? '',
            data:       $body['data'] ?? null,
            raw:        $body,
        );
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getRequestId(): ?string
    {
        if (is_array($this->data)) {
            return $this->data['id'] ?? $this->data['request_id'] ?? null;
        }
        return null;
    }

    public function toArray(): array
    {
        return $this->raw;
    }
}
