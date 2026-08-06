<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupService
{
    protected $backupPath;
    protected $dbConnection;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->dbConnection = config('database.connections.mysql');

        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    public function createBackup(): array
    {
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filePath = $this->backupPath . '/' . $filename;

        try {
            $command = $this->buildDumpCommand($filePath);
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(3600);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            Log::info('Backup created successfully', [
                'filename' => $filename,
                'size' => filesize($filePath),
                'user' => auth()->id()
            ]);

            return [
                'success' => true,
                'filename' => $filename,
                'path' => $filePath,
                'size' => filesize($filePath)
            ];

        } catch (\Exception $e) {
            Log::error('Backup failed', [
                'error' => $e->getMessage(),
                'user' => auth()->id()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function restoreBackup(string $filename): array
    {
        $filePath = $this->backupPath . '/' . $filename;

        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'error' => 'فایل پشتیبان یافت نشد.'
            ];
        }

        try {
            // بررسی امنیتی: فقط فایل‌های با پسوند .sql مجاز هستند
            if (pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
                throw new \Exception('فایل غیرمجاز است.');
            }

            $command = $this->buildRestoreCommand($filePath);
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(3600);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            Log::info('Restore completed successfully', [
                'filename' => $filename,
                'user' => auth()->id()
            ]);

            return [
                'success' => true,
                'message' => 'بازیابی با موفقیت انجام شد.'
            ];

        } catch (\Exception $e) {
            Log::error('Restore failed', [
                'filename' => $filename,
                'error' => $e->getMessage(),
                'user' => auth()->id()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function listBackups(): array
    {
        $files = glob($this->backupPath . '/*.sql');
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'path' => $file,
                'size' => filesize($file),
                'created_at' => date('Y-m-d H:i:s', filemtime($file))
            ];
        }

        // مرتب‌سازی بر اساس تاریخ (جدیدترین اول)
        usort($backups, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backups;
    }

    public function deleteBackup(string $filename): array
    {
        $filePath = $this->backupPath . '/' . $filename;

        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'error' => 'فایل یافت نشد.'
            ];
        }

        if (unlink($filePath)) {
            Log::info('Backup deleted', [
                'filename' => $filename,
                'user' => auth()->id()
            ]);

            return [
                'success' => true,
                'message' => 'فایل پشتیبان حذف شد.'
            ];
        }

        return [
            'success' => false,
            'error' => 'خطا در حذف فایل.'
        ];
    }

    protected function buildDumpCommand(string $filePath): string
    {
        $db = $this->dbConnection;

        return sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg($db['host'] ?? '127.0.0.1'),
            escapeshellarg($db['port'] ?? '3306'),
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['database']),
            escapeshellarg($filePath)
        );
    }

    protected function buildRestoreCommand(string $filePath): string
    {
        $db = $this->dbConnection;

        return sprintf(
            'mysql --host=%s --port=%s --user=%s --password=%s %s < %s',
            escapeshellarg($db['host'] ?? '127.0.0.1'),
            escapeshellarg($db['port'] ?? '3306'),
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['database']),
            escapeshellarg($filePath)
        );
    }
}