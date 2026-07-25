<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bootcamp;
use App\Models\Course;
use App\Models\Picture;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function store(Request $request, string $type, int $id): RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|max:20480',
            'type' => 'required|in:thumbnail,gallery',
            'description' => 'nullable|string|max:255',
        ]);

        // Determine the model type
        $modelType = $type === 'course' ? Course::class : Bootcamp::class;
        $model = $modelType::findOrFail($id);

        $url = ImageService::uploadAndCompress($request->file('image'), 'pictures', 1200, 80);

        // Create picture with uploaded file URL
        Picture::create([
            'pictureable_type' => $modelType,
            'pictureable_id' => $id,
            'url' => $url,
            'type' => $request->type,
            'description' => $request->description,
            'order' => $model->pictures()->max('order') + 1,
        ]);

        return back()->with('success', __('app.msg_success_gambar_berhasil_ditambahkan'));
    }

    public function destroy(Picture $picture): RedirectResponse
    {
        // Optionally delete the file from storage
        if ($picture->url && str_starts_with($picture->url, '/storage/')) {
            $path = str_replace('/storage/', '', $picture->url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $picture->delete();

        return back()->with('success', __('app.msg_success_gambar_berhasil_dihapus'));
    }
}
