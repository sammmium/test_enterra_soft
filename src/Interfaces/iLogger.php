<?php

namespace src\interfaces;

interface iLogger
{
    public static function log(string $type = 'info', string $message);
}