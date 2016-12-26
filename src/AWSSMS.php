<?php
/**
 * Created by PhpStorm.
 * User: peec
 * Date: 12/26/16
 * Time: 3:54 PM
 */

namespace NotificationChannels\AWS;

use NotificationChannels\AWS\Exceptions\CouldNotSendNotification;



class AWSSMS
{

    /**
     * Default 'from' from config.
     * @var string
     */
    protected $from;

    public function __construct(\Aws\Sns\SnsClient $snsClient, $from)
    {
        $this->snsClient = $snsClient;
        $this->from = $from;
    }
    /**
     * Send a TwilioMessage to the a phone number.
     *
     * @param  TwilioMessage  $message
     * @param  $to
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function sendMessage(AWSSMSMessage $message, $to)
    {
        if ($message instanceof AWSSMSMessage) {
            return $this->sendSmsMessage($message, $to);
        }
        throw CouldNotSendNotification::invalidMessageObject($message);
    }
    protected function sendSmsMessage(AWSSMSMessage $message, $to)
    {
        $args = array(
            "SenderID" => $this->getFrom($message),
            "SMSType" => $message->type,
            "Message" => $message->content,
            "PhoneNumber" => $to
        );


        return $this->snsClient->publish($args);
    }
    protected function getFrom($message)
    {
        if (! $from = $message->from ?: $this->from) {
            throw CouldNotSendNotification::missingFrom();
        }
        return $from;
    }
}