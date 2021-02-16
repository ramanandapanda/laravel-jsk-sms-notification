<?php

namespace NotificationChannels\JSKSMS\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($message)
    {
        return new static('JSKSMS Response: '.$message);
    }

    public static function serviceNotAvailable($message): self
    {
        return new static($message);
    }

    public static function phoneNumberNotProvided(): self
    {
        return new static('No phone number was provided.');
    }
}
