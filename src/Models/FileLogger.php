<?php

namespace src\Models;

class FileLogger
{
    private $path;

    private $fileName = 'events.log';

    private $pathFile;

    public function __construct()
    {
        $this->path = dirname(dirname(__DIR__)) . '/logs/';
        $this->pathFile = $this->path . $this->fileName;
    }

    public function log(string $type = 'info', string $message): void
    {
        if (method_exists($this, $type)) {
            $this->{$type}($message);
        } else {
            $this->set($this->prepare('[UNKNOWN] ' . $message));
        }
    }

    public function info(string $message): void
    {
        $this->set($this->prepare('[INFO] ' . $message));
    }

    public function error(string $message): void
    {
        $this->set($this->prepare('[ERROR] ' . $message));
    }

    protected function prepare(string $message): string
    {
        $now = date('Y-m-d H:i:s');
        return $now . ' ' . $message;
    }

    private function set(string $message): void
    {
        if (file_exists($this->pathFile)) {
            file_put_contents($this->pathFile, $message . PHP_EOL, FILE_APPEND);
        }
    }
}
