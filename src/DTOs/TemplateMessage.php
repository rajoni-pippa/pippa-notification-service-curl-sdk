<?php

namespace Pippa\NotificationSdkCurl\DTOs;

/**
 * Represents a template-based notification message.
 *
 * Usage (mirrors Courier SDK style):
 *
 *   new TemplateMessage([
 *       'to'       => [Recipient::email('user@example.com')],
 *       'template' => 'welcome_email',
 *       'data'     => ['name' => 'Rahim'],
 *   ])
 */
class TemplateMessage
{
    /** @var Recipient[] */
    public readonly array $to;
    public readonly string $template;
    public readonly array $data;
    public readonly array $channels;
    public readonly ?string $subject;
    public readonly ?string $body;
    public readonly ?string $otp;
    public readonly ?string $name;

    public function __construct(array $params)
    {
        // 'to' can be a single Recipient or an array of Recipients
        $to = $params['to'] ?? [];
        $this->to = is_array($to) ? $to : [$to];

        $this->template = $params['template'] ?? '';
        $this->data     = $params['data'] ?? [];
        $this->channels = $params['channels'] ?? [];
        $this->subject  = $params['subject'] ?? null;
        $this->body     = $params['body'] ?? null;
        $this->otp      = $params['otp'] ?? null;
        $this->name     = $params['name'] ?? null;
    }

    public function toArray(): array
    {
        $recipients = array_map(
            fn(Recipient $r) => $r->toArray(),
            $this->to
        );

        $payload = [
            'recipients'    => $recipients,
            'template_name' => $this->template,
            'dynamic_data'  => $this->data,
        ];

        if (!empty($this->channels)) {
            $payload['channels'] = $this->channels;
        }

        if ($this->subject !== null) {
            $payload['subject'] = $this->subject;
        }

        if ($this->body !== null) {
            $payload['body'] = $this->body;
        }

        if ($this->otp !== null) {
            $payload['otp'] = $this->otp;
        }

        if ($this->name !== null) {
            $payload['name'] = $this->name;
        }

        return $payload;
    }
}
