# Notification SDK — Plain PHP / cURL

Pure PHP SDK for the **Notification Service** API.  
**Zero dependencies** — works in any PHP 8.1+ environment without Laravel or Guzzle.

---

## Installation

```bash
composer require Pippa\notification-sdk-curl
```

---

## Setup

```php
<?php
require 'vendor/autoload.php';

use Pippa\NotificationSdkCurl\NotificationClient;

$client = new NotificationClient(
    apiKey:    'your_api_key',
    secretKey: 'your_secret_key',
);
```

---

## Usage

### Send Email

```php
$response = $client->sendEmail(
    email:    'user@example.com',
    template: 'welcome_email',
    data:     ['name' => 'Rahim']
);
```

### Send SMS

```php
$response = $client->sendSms(
    phone:    '+8801700000000',
    template: 'otp_sms',
    data:     ['otp' => '1234']
);
```

### Send WhatsApp

```php
$response = $client->sendWhatsapp(
    whatsapp: '+8801700000000',
    template: 'whatsapp_template',
    data:     ['name' => 'Rahim']
);
```

### Send Push Notification

```php
$response = $client->sendPush(
    userId:   'user_123',
    template: 'push_template',
    data:     ['title' => 'Hello!', 'body' => 'You have a new message.']
);
```

### Send In-App Notification

```php
$response = $client->sendInApp(
    userId:   'user_123',
    template: 'order_update',
    data:     ['order_id' => 'ORD-456', 'status' => 'Shipped']
);
```

### Multi-channel (Courier-style)

```php
use Pippa\NotificationSdkCurl\Requests\SendMessageRequest;
use Pippa\NotificationSdkCurl\DTOs\TemplateMessage;
use Pippa\NotificationSdkCurl\DTOs\Recipient;

$response = $client->send(
    new SendMessageRequest([
        'message' => new TemplateMessage([
            'to' => [
                Recipient::email('user@example.com'),
                Recipient::phone('+8801700000000'),
                Recipient::userId('user_123'),
                Recipient::whatsapp('+8801747436390'),
            ],
            'template' => 'welcome_notification',
            'data'     => ['name' => 'Rahim'],
        ]),
    ])
);

if ($response->isSuccess()) {
    echo 'Sent! ID: ' . $response->getRequestId();
}
```

---

## Exception Handling

```php
use Pippa\NotificationSdkCurl\Exceptions\NotificationException;

try {
    $client->sendEmail('user@example.com', 'my_template');
} catch (NotificationException $e) {
    echo $e->getMessage();    // error message
    echo $e->getCode();       // HTTP status code
    print_r($e->getErrors()); // validation errors array
}
```

---

## Available Methods

| Method                                       | Description                    |
| -------------------------------------------- | ------------------------------ |
| `send(SendMessageRequest)`                   | Full control                   |
| `sendEmail($email, $template, $data)`        | Send email via template        |
| `sendSms($phone, $template, $data)`          | Send SMS via template          |
| `sendWhatsapp($whatsapp, $template, $data)`  | Send WhatsApp via template     |
| `sendPush($userId, $template, $data)`        | Send push notification         |
| `sendInApp($userId, $template, $data)`       | Send in-app notification       |
| `sendMulti($recipients[], $template, $data)` | Multi-channel, multi-recipient |

---

## Recipient Helpers

```php
use Pippa\NotificationSdkCurl\DTOs\Recipient;

Recipient::email('user@example.com')
Recipient::phone('+8801700000000')
Recipient::userId('user_123')
Recipient::make(['email' => '...', 'phone' => '...', 'user_id' => '...'])
Recipient::make([...])->only(['email', 'sms'])  
```

---

## License

MIT