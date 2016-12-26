<?php

namespace NotificationChannels\AWS;

use Illuminate\Support\Arr;

class AWSSMSMessage
{
    const TYPE_TRANSACTIONAL = 'Transactional';
    const TYPE_PROMOTIONAL = 'Promotional';


    /**
     * The message content.
     *
     * @var string
     */
    public $content;
    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;


    /**
     * The type.
     *
     * @var string
     */
    public $type = self::TYPE_TRANSACTIONAL;

    /**
     * @param string $content
     *
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }
    /**
     * Create a new message instance.
     *
     * @param  string  $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }
    /**
     * Set the message content.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }
    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string  $from
     *
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;
        return $this;
    }


    /**
     * Set the type content.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }
}
