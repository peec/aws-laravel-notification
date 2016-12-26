<?php

namespace NotificationChannels\AWS;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use NotificationChannels\AWS\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Events\NotificationFailed;

class AWSSMSChannel
{

    /**
     * @var AWSSMS
     */
    protected $awsSms;
    /**
     * @var Dispatcher
     */
    protected $events;
    /**
     * TwilioChannel constructor.
     *
     * @param Twilio  $twilio
     * @param Dispatcher  $events
     */
    public function __construct(AWSSMS $awsSms, Dispatcher $events)
    {
        $this->awsSms = $awsSms;
        $this->events = $events;
    }
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $to = $this->getTo($notifiable);
            $message = $notification->toAwsSms($notifiable);
            if (is_string($message)) {
                $message = new AWSSMSMessage($message);
            }
            if (! $message instanceof AWSSMSMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }
            return $this->awsSms->sendMessage($message, $to);
        } catch (Exception $exception) {
            $this->events->fire(
                new NotificationFailed($notifiable, $notification, 'awssms', ['message' => $exception->getMessage()])
            );
        }
    }
    protected function getTo($notifiable)
    {
        if ($notifiable->routeNotificationFor('aws')) {
            return $notifiable->routeNotificationFor('aws');
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }
        throw CouldNotSendNotification::invalidReceiver();
    }

}
