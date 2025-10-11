<?php

class Logger
{
    private static $instance;
    private $logFile;
    private $enabled;
    private $maxFileSize;

    private function __construct()
    {
        $config = require __DIR__ . '/../config/logging.php';
        $this->enabled = $config['enabled'];
        $this->logFile = $config['log_file'];
        $this->maxFileSize = $config['max_file_size'];

        // Создаем директорию для логов если не существует
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function log($level, $message, $context = [])
    {
        if (!$this->enabled) {
            return;
        }

        // Ротация логов
        $this->rotateLog();

        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logMessage = "[$timestamp] [$level] $message $contextStr" . PHP_EOL;

        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    public function debug($message, $context = [])
    {
        $this->log('DEBUG', $message, $context);
    }

    public function info($message, $context = [])
    {
        $this->log('INFO', $message, $context);
    }

    public function warning($message, $context = [])
    {
        $this->log('WARNING', $message, $context);
    }

    public function error($message, $context = [])
    {
        $this->log('ERROR', $message, $context);
    }

    private function rotateLog()
    {
        if (file_exists($this->logFile) && filesize($this->logFile) >= $this->maxFileSize) {
            $backupFile = $this->logFile . '.' . date('Y-m-d_His');
            rename($this->logFile, $backupFile);
        }
    }
}

// Функции-помощники для быстрого доступа
function log_debug($message, $context = []) {
    Logger::getInstance()->debug($message, $context);
}

function log_info($message, $context = []) {
    Logger::getInstance()->info($message, $context);
}

function log_warning($message, $context = []) {
    Logger::getInstance()->warning($message, $context);
}

function log_error($message, $context = []) {
    Logger::getInstance()->error($message, $context);
}