<?php

namespace NotificationChannels\AWS\Exceptions;

use NotificationChannels\AWS\AWSSMSMessage;

class CouldNotSendNotification extends \Exception
{
    /**
     * @param mixed $message
     *
     * @return static
     */
    public static function invalidMessageObject($message)
    {
        $className = get_class($message) ?: 'Unknown';
        return new static(
            "Notification was not sent. Message object class `{$className}` is invalid. It should
            be either `".AWSSMSMessage::class.'`.');
    }

    public static function serviceRespondedWithAnError($response)
    {
        return new static("Descriptive error message.");
    }


    /**
     * @return static
     */
    public static function missingFrom()
    {
        return new static('Notification was not sent. Missing `from` number.');
    }


    /**
     * @return static
     */
    public static function invalidReceiver()
    {
        return new static(
            'The notifiable did not have a receiving phone number.'
        );
    }
}
