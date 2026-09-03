<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupService
{
    protected string $backupPath;
    protected array $dbConnection;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->dbConnection = config('database.connections.mysql');

        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    /**
     * ایجاد بکاپ جدید
     */
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

            if (!file_exists($filePath) || filesize($filePath) === 0) {
                throw new \Exception('فایل بکاپ ایجاد نشد یا خالی است.');
            }

            Log::info('Backup created successfully', [
                'filename' => $filename,
                'size' => filesize($filePath),
                'user' => auth()->id(),
            ]);

            return [
                'success' => true,
                'filename' => $filename,
                'path' => $filePath,
                'size' => filesize($filePath),
            ];

        } catch (\Throwable $e) {
            Log::error('Backup failed', [
                'error' => $e->getMessage(),
                'user' => auth()->id(),
            ]);

            if (file_exists($filePath) && filesize($filePath) === 0) {
                @unlink($filePath);
            }

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * بازیابی بکاپ
     */
    public function restoreBackup(string $filename): array
    {
        $filename = basename($filename);

        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
            return [
                'success' => false,
                'error' => 'فقط فایل‌های SQL مجاز هستند.',
            ];
        }

        $filePath = $this->backupPath . '/' . $filename;

        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'error' => 'فایل پشتیبان یافت نشد.',
            ];
        }

        try {
            $command = $this->buildRestoreCommand($filePath);

            $process = Process::fromShellCommandline($command);
            $process->setTimeout(3600);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            Log::info('Restore completed successfully', [
                'filename' => $filename,
                'user' => auth()->id(),
            ]);

            return [
                'success' => true,
                'message' => 'بازیابی با موفقیت انجام شد.',
            ];

        } catch (\Throwable $e) {
            Log::error('Restore failed', [
                'filename' => $filename,
                'error' => $e->getMessage(),
                'user' => auth()->id(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * لیست بکاپ‌ها
     */
    public function listBackups(): array
    {
        if (!is_dir($this->backupPath)) {
            return [];
        }

        $files = glob($this->backupPath . '/*.sql') ?: [];
        $backups = [];

        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }

            $backups[] = [
                'filename' => basename($file),
                'path' => $file,
                'size' => filesize($file),
                'created_at' => date('Y-m-d H:i:s', filemtime($file)),
            ];
        }

        usort($backups, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backups;
    }

    /**
     * حذف بکاپ
     */
    public function deleteBackup(string $filename): array
    {
        $filename = basename($filename);

        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
            return [
                'success' => false,
                'error' => 'فایل غیرمجاز است.',
            ];
        }

        $filePath = $this->backupPath . '/' . $filename;

        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'error' => 'فایل بکاپ یافت نشد.',
            ];
        }

        if (@unlink($filePath)) {
            Log::info('Backup deleted', [
                'filename' => $filename,
                'user' => auth()->id(),
            ]);

            return [
                'success' => true,
                'message' => 'فایل پشتیبان حذف شد.',
            ];
        }

        return [
            'success' => false,
            'error' => 'خطا در حذف فایل.',
        ];
    }

    /**
     * ساخت دستور mysqldump
     */
    protected function buildDumpCommand(string $filePath): string
    {
        $db = $this->dbConnection;

        return sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg($db['host'] ?? '127.0.0.1'),
            escapeshellarg($db['port'] ?? '3306'),
            escapeshellarg($db['username'] ?? ''),
            escapeshellarg($db['password'] ?? ''),
            escapeshellarg($db['database'] ?? ''),
            escapeshellarg($filePath)
        );
    }

    /**
     * ساخت دستور mysql برای Restore
     */
    protected function buildRestoreCommand(string $filePath): string
    {
        $db = $this->dbConnection;

        return sprintf(
            'mysql --host=%s --port=%s --user=%s --password=%s %s < %s',
            escapeshellarg($db['host'] ?? '127.0.0.1'),
            escapeshellarg($db['port'] ?? '3306'),
            escapeshellarg($db['username'] ?? ''),
            escapeshellarg($db['password'] ?? ''),
            escapeshellarg($db['database'] ?? ''),
            escapeshellarg($filePath)
        );
    }
}
