<?php

namespace Pippa\NotificationSdkCurl\DTOs;

/**
 * Represents a single notification recipient.
 *
 * Usage:
 *   Recipient::email('user@example.com')
 *   Recipient::phone('+8801700000000')
 *   Recipient::userId('user_123')
 *   Recipient::make(['email' => '...', 'phone' => '...', 'user_id' => '...'])
 */
class Recipient
{
    private array $data = [];

    private function __construct(array $data)
    {
        $this->data = array_filter($data, fn($v) => $v !== null && $v !== '');
    }

    // ── Static constructors ──────────────────────────────────

    public static function email(string $email): static
    {
        return new static(['email' => $email]);
    }

    public static function phone(string $phone): static
    {
        return new static(['phone' => $phone]);
    }

    public static function whatsapp(string $whatsapp): static
    {
        return new static(['whatsapp' => $whatsapp]);
    }

    public static function push(string $push): static
    {
        return new static(['push' => $push]);
    }

    public static function userId(string $userId): static
    {
        return new static(['user_id' => $userId]);
    }

    /**
     * Build a recipient from a raw array.
     * Useful when a single recipient has multiple channels:
     *
     *   Recipient::make(['email' => '...', 'phone' => '...'])
     */
    public static function make(array $data): static
    {
        return new static($data);
    }

    /**
     * Restrict this recipient to specific channels only.
     *
     *   Recipient::email('...')->only(['email'])
     */
    public function only(array $channels): static
    {
        $this->data['channels'] = $channels;
        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
