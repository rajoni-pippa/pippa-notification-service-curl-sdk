<?php

namespace Pippa\NotificationSdkCurl;

use Pippa\NotificationSdkCurl\DTOs\NotificationResponse;
use Pippa\NotificationSdkCurl\DTOs\Recipient;
use Pippa\NotificationSdkCurl\DTOs\TemplateMessage;
use Pippa\NotificationSdkCurl\Exceptions\NotificationException;
use Pippa\NotificationSdkCurl\Requests\SendMessageRequest;


class NotificationClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $secretKey;
    protected int $timeout;

    public function __construct(
        string $baseUrl,
        string $apiKey,
        string $secretKey,
        int $timeout = 30
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
        $this->timeout = $timeout;
    }


    public function send(SendMessageRequest $request): NotificationResponse
    {
        return $this->post('/v1/notification/send', $request->toArray());
    }


    public function sendEmail(
        string $email,
        string $template,
        array $data = [],
        array $extra = []
    ): NotificationResponse {
        return $this->send(new SendMessageRequest([
            'message' => new TemplateMessage([
                'to' => [Recipient::email($email)],
                'template' => $template,
                'data' => $data,
                ...$extra,
            ]),
        ]));
    }

    public function sendSms(
        string $phone,
        string $template,
        array $data = [],
        array $extra = []
    ): NotificationResponse {
        return $this->send(new SendMessageRequest([
            'message' => new TemplateMessage([
                'to' => [Recipient::phone($phone)],
                'template' => $template,
                'data' => $data,
                ...$extra,
            ]),
        ]));
    }


    public function sendInApp(
        string $userId,
        string $template,
        array $data = [],
        array $extra = []
    ): NotificationResponse {
        return $this->send(new SendMessageRequest([
            'message' => new TemplateMessage([
                'to' => [Recipient::userId($userId)],
                'template' => $template,
                'data' => $data,
                ...$extra,
            ]),
        ]));
    }


    public function sendMulti(
        array $recipients,
        string $template,
        array $data = [],
        array $extra = []
    ): NotificationResponse {
        return $this->send(new SendMessageRequest([
            'message' => new TemplateMessage([
                'to' => $recipients,
                'template' => $template,
                'data' => $data,
                ...$extra,
            ]),
        ]));
    }


    protected function post(string $endpoint, array $payload): NotificationResponse
    {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init($url);

        $headers = [];
        foreach ($this->buildHeaders() as $key => $value) {
            $headers[] = "{$key}: {$value}";
        }

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $rawResponse = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($rawResponse === false) {
            throw new NotificationException("cURL error: {$curlError}", 0);
        }

        $body = json_decode($rawResponse, true) ?? [];

        if ($statusCode >= 400) {
            $message = $body['message'] ?? "HTTP Error {$statusCode}";
            $errors = $body['errors'] ?? [];
            throw new NotificationException($message, $statusCode, $errors);
        }

        return NotificationResponse::fromArray($body, $statusCode);
    }

    protected function buildHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'apiKey' => $this->apiKey,
            'secretKey' => $this->secretKey,
        ];
    }
}
