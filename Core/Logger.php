<?php
namespace Core;

use Exception;

class Logger
{
    /** @var string Dossier et fichier de log */
    protected string $logFile;
    protected ?string $logDirName= null;

    public function __construct(?string $logFile = "logs/database.log",?string $logDirName=null)
    {
        //$this->logDirName=$logDirName? $logDirName :"/".__DIR__."/";
        $this->logFile =$logFile;


        // Crée le dossier s'il n'existe pas
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        // Crée le fichier s'il n'existe pas
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, "=== Database Logs ===\n");
        }
    }

    /**
     * Log une erreur avec horodatage
     */
    public function logError(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] ERROR: {$message}\n";
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Log une info (optionnel)
     */
    public function logInfo(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] INFO: {$message}\n";
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}



