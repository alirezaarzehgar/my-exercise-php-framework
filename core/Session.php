<?php

namespace app\core;

class Session
{
    public const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start();
        $flashMessges = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessges as $key => &$flashMessage)
            $flashMessage['remove'] = true;

        $_SESSION[self::FLASH_KEY] = $flashMessges;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message,
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'];
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY];
        foreach ($flashMessages as $key => &$flashMessage)
            if ($flashMessage['remove'])
                unset($flashMessages[$key]);

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}
