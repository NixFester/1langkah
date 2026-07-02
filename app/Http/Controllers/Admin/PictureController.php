<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Picture;
use App\Models\Course;
use App\Models\Bootcamp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PictureController extends Controller
{
    public function store(Request $request, string $type, int $id): RedirectResponse
    {
        $request->validate([
            'image_url' => 'required|url|max:500',
            'type' => 'required|in:thumbnail,gallery',
            'description' => 'nullable|string|max:255',
        ]);

        // Determine the model type
        $modelType = $type === 'course' ? Course::class : Bootcamp::class;
        $model = $modelType::findOrFail($id);

        // Create picture with URL directly
        Picture::create([
            'pictureable_type' => $modelType,
            'pictureable_id' => $id,
            'url' => trim($request->image_url),
            'type' => $request->type,
            'description' => $request->description,
            'order' => $model->pictures()->max('order') + 1,
        ]);

        return back()->with('success', 'Gambar berhasil ditambahkan.');
    }

    public function destroy(Picture $picture): RedirectResponse
    {
        // Optionally delete the file from storage
        if ($picture->url && str_contains($picture->url, '/storage/pictures/')) {
            $path = str_replace(asset('storage') . '/', '', $picture->url);
            if (\Storage::disk('public')->exists($path)) {
                \Storage::disk('public')->delete($path);
            }
        }

        $picture->delete();
        return back()->with('success', 'Gambar berhasil dihapus.');
    }
}
