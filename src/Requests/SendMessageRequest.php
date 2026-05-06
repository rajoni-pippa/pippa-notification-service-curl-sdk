<?php

namespace Pippa\NotificationSdkCurl\Requests;

use Pippa\NotificationSdkCurl\DTOs\TemplateMessage;

/**
 * Top-level request wrapper — mirrors Courier SDK style.
 *
 * Usage:
 *   new SendMessageRequest([
 *       'message' => new TemplateMessage([...])
 *   ])
 */
class SendMessageRequest
{
    public readonly TemplateMessage $message;

    public function __construct(array $params)
    {
        $this->message = $params['message'];
    }

    public function toArray(): array
    {
        return $this->message->toArray();
    }
}
