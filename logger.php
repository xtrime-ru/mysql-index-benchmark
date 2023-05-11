<?php

class Logger
{
    public static function log(string $message, array $context = []): void
    {
        echo sprintf(
            "[%s] %s %s\n",
            date('Y-m-d H:i:s'),
            $message,
            $context ? "\n" . json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : ""
        );
    }
}