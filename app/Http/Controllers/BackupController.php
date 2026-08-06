<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    // نمایش صفحه بکاپ
    public function index()
    {
        // فقط مدیر کل دسترسی داشته باشد
        if (auth()->user()->role !== 'admin') {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $backups = [];
        $files = Storage::disk('local')->files('backups');
        
        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => Storage::disk('local')->size($file),
                'date' => Storage::disk('local')->lastModified($file),
            ];
        }

        // مرتب‌سازی بر اساس تاریخ (جدیدترین اول)
        usort($backups, function($a, $b) {
            return $b['date'] - $a['date'];
        });

        return view('admin.backup', compact('backups'));
    }

    // ایجاد بکاپ جدید
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        // ایجاد پوشه بکاپ اگر وجود ندارد
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // گرفتن بکاپ از دیتابیس
        $command = sprintf(
            'mysqldump -u %s -p%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            $path
        );

        exec($command);

        return redirect()->route('backup.index')
                         ->with('success', 'بکاپ با موفقیت گرفته شد.');
    }

    // دانلود فایل بکاپ
    public function download($filename)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $path = storage_path('app/backups/' . $filename);
        
        if (!file_exists($path)) {
            return redirect()->route('backup.index')
                             ->with('error', 'فایل بکاپ یافت نشد.');
        }

        return response()->download($path);
    }

    // حذف فایل بکاپ
    public function delete($filename)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            unlink($path);
            return redirect()->route('backup.index')
                             ->with('success', 'فایل بکاپ با موفقیت حذف شد.');
        }

        return redirect()->route('backup.index')
                         ->with('error', 'فایل بکاپ یافت نشد.');
    }

    // بازیابی از بکاپ
    public function restore($filename)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $path = storage_path('app/backups/' . $filename);
        
        if (!file_exists($path)) {
            return redirect()->route('backup.index')
                             ->with('error', 'فایل بکاپ یافت نشد.');
        }

        // بازیابی دیتابیس
        $command = sprintf(
            'mysql -u %s -p%s %s < %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            $path
        );

        exec($command);

        return redirect()->route('backup.index')
                         ->with('success', 'دیتابیس با موفقیت بازیابی شد.');
    }
}