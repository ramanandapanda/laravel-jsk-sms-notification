<?php

namespace NotificationChannels\JSKSMS;

use Illuminate\Support\Arr;

class JSKSMSMessage
{
    protected $payload = [];

    /**
     * @param string $message
     */
    public function __construct(string $message = '')
    {
        $this->payload['msg'] = $message;
        $this->payload['json'] = 1;
    }

    /**
     * Get the payload value for a given key.
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function getPayloadValue(string $key)
    {
        return $this->payload[$key] ?? null;
    }

    /**
     * Return new SMS77Message object.
     *
     * @param string $message
     */
    public static function create(string $message = ''): self
    {
        return new self($message);
    }

    /**
     * Returns if recipient number is given or not.
     *
     * @return bool
     */
    public function hasToNumber(): bool
    {
        return isset($this->payload['to']);
    }

    /**
     * Set message content.
     *
     * @param string $message
     */
    public function content(string $message): self
    {
        $this->payload['msg'] = $message;

        return $this;
    }

    /**
     * Set recipient phone number.
     *
     * @param string $to
     */
    public function to(string $to): self
    {
        $this->payload['to'] = $to;

        return $this;
    }

}
