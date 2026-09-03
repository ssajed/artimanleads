<?php

namespace App\Http\Controllers;

use App\Services\BackupService;

class BackupController extends Controller
{
    protected BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * نمایش لیست بکاپ‌ها
     */
    public function index()
    {
        $backups = $this->backupService->listBackups();

        return view('admin.backup', compact('backups'));
    }

    /**
     * ایجاد بکاپ جدید
     */
    public function create()
    {
        $result = $this->backupService->createBackup();

        if (!$result['success']) {
            return redirect()
                ->route('backup.index')
                ->with('error', 'خطا در ایجاد بکاپ: ' . $result['error']);
        }

        return redirect()
            ->route('backup.index')
            ->with('success', 'بکاپ با موفقیت ایجاد شد: ' . $result['filename']);
    }

    /**
     * دانلود بکاپ
     */
    public function download(string $filename)
    {
        $filename = basename($filename);

        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
            abort(404, 'فایل بکاپ معتبر نیست.');
        }

        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'فایل بکاپ یافت نشد.');
        }

        return response()->download($path);
    }

    /**
     * بازیابی بکاپ
     */
    public function restore(string $filename)
    {
        $filename = basename($filename);

        $result = $this->backupService->restoreBackup($filename);

        if (!$result['success']) {
            return redirect()
                ->route('backup.index')
                ->with('error', 'خطا در بازیابی بکاپ: ' . $result['error']);
        }

        return redirect()
            ->route('backup.index')
            ->with('success', 'دیتابیس با موفقیت از بکاپ بازیابی شد.');
    }

    /**
     * حذف بکاپ
     */
    public function delete(string $filename)
    {
        $filename = basename($filename);

        $result = $this->backupService->deleteBackup($filename);

        if (!$result['success']) {
            return redirect()
                ->route('backup.index')
                ->with('error', 'خطا در حذف بکاپ: ' . $result['error']);
        }

        return redirect()
            ->route('backup.index')
            ->with('success', 'فایل بکاپ با موفقیت حذف شد.');
    }
}
