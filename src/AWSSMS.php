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

    /**
     * The maximum amount in USD that you are willing to spend to send the SMS message. Amazon SNS will not send the message if it determines that doing so would incur a cost that exceeds the maximum price.
     * This attribute has no effect if your month-to-date SMS costs have already exceeded the limit set for the MonthlySpendLimit attribute, which you set by using the SetSMSAttributes request.
     * If you are sending the message to an Amazon SNS topic, the maximum price applies to each message delivery to each phone number that is subscribed to the topic.
     * @var
     */
    protected $maxPrice;

    public function __construct(\Aws\Sns\SnsClient $snsClient, $from, $maxPrice)
    {
        $this->snsClient = $snsClient;
        $this->from = $from;
        $this->maxPrice = $maxPrice;
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

        $args = [
            'Message' => $message->content,
            'PhoneNumber' => $to,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' =>  $this->getFrom($message)
                ],

                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' =>  $message->type
                ],
                'AWS.SNS.SMS.MaxPrice' => [
                    'DataType' => 'String',
                    'StringValue' =>  $this->maxPrice
                ]
            ]
        ];



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