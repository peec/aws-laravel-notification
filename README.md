# AWS SMS Laravel notification

Send sms trough AWS SNS.


## Contents

- [Installation](#installation)
	- [Setting up the AWSSMS service](#setting-up-the-AWSSMS-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation


You can install the package via composer:

``` bash
composer require peec/aws-laravel-notification
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\AWS\AWSSMSServiceProvider::class,
],
```


### Setting up the AWSSMS service


Add  to your `config/services.php`:

```php
// config/services.php
...
'awssms' => [
    'key' => env('AWSSMS_KEY'),
    'secret' => env('AWSSMS_SECRET'),
    'region' => env('AWSSMS_REGION'),
    'from' => env('AWSSMS_FROM'), // optional
],
...
```


## Usage





Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\AWS\AWSSMSChannel;
use NotificationChannels\AWS\AWSSMSMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [AWSSMSChannel::class];
    }

    public function toAwsSms($notifiable)
    {
        return (new AWSSMSMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

In order to let your Notification know which phone are you sending/calling to, the channel will look for the `phone_number` attribute of the Notifiable model. If you want to override this behaviour, 
add the `routeNotificationForAws` method to your Notifiable model.

```php
public function routeNotificationForAws()
{
    return '+1234567890';
}
```


### Available methods

- `from('')`: Accepts a phone to use as the notification sender.
- `content('')`: Accepts a string value for the notification body.
- `type('Transactional')`: Either Transactional or Promotional. See aws docs for SNS SMS. The pricing of these vary.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email kjelkenes@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Petter Kjelkenes](https://github.com/peec)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
