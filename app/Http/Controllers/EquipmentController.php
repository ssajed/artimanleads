<?php

namespace App\Http\Controllers;

use App\Models\EquipmentDetail;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:chiller,cooling_tower',
        ]);

        $project = \App\Models\Project::findOrFail($request->project_id);
        $this->authorize('update', $project);

        $file = $request->file('photo');
        $filename = "{$request->type}_{$project->id}_" . time() . '.jpg';
        $path = "public/equipment-photos/{$filename}";

        // فشرده‌سازی
        $image = Image::read($file);
        $image->scale(width: 1200);
        $image->save(storage_path("app/{$path}"), quality: 70);

        // ذخیره یا آپدیت در دیتابیس
        EquipmentDetail::updateOrCreate(
            ['project_id' => $project->id, 'type' => $request->type],
            ['nameplate_photo_path' => $path]
        );

        return response()->json(['success' => true, 'path' => Storage::url($path)]);
    }
}