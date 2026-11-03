<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    private function processImagePath(?string $imagePath): ?string
    {
        if (!$imagePath) return null;
        if (str_starts_with($imagePath, 'data:image')) {
            try {
                $commaPos = strpos($imagePath, ',');
                if ($commaPos !== false) {
                    $header = substr($imagePath, 0, $commaPos);
                    $base64Data = substr($imagePath, $commaPos + 1);
                    $decoded = base64_decode($base64Data);
                    if ($decoded !== false && strlen($decoded) > 0) {
                        $ext = 'jpg';
                        if (preg_match('/data:image\/([a-zA-Z0-9\+\-\.]+);/i', $header, $m)) {
                            $rawExt = strtolower($m[1]);
                            if (in_array($rawExt, ['jpeg', 'jpg', 'png', 'gif', 'webp'])) {
                                $ext = ($rawExt === 'jpeg') ? 'jpg' : $rawExt;
                            }
                        }
                        $filename = 'slider_' . time() . '_' . uniqid() . '.' . $ext;
                        $uploadDir = public_path('uploads/sliders');
                        if (!File::isDirectory($uploadDir)) {
                            File::makeDirectory($uploadDir, 0755, true, true);
                        }
                        File::put($uploadDir . '/' . $filename, $decoded);
                        return '/uploads/sliders/' . $filename;
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('SliderController processImagePath failed: ' . $e->getMessage());
            }
        }
        return $imagePath;
    }

    public function index()
    {
        return response()->json(Slider::orderBy('sort_order', 'asc')->get());
    }

    public function publicSliders()
    {
        return response()->json(Slider::where('is_active', true)->orderBy('sort_order', 'asc')->get());
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak. Hanya Admin yang berhak mengunggah banner slider.'], 403);
        }

        $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image_path' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $title = !empty(trim($request->title ?? ''))
            ? trim($request->title)
            : 'Dokumentasi Haul & Rojabiyyah ' . date('Y');

        $processedImage = $this->processImagePath($request->image_path);

        $slider = Slider::create([
            'title' => $title,
            'description' => $request->description,
            'image_path' => $processedImage,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);
        AuditLog::log('create', 'Slider', $slider->id);

        return response()->json([
            'message' => 'Banner slider berhasil dibuat.',
            'slider' => $slider,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak. Hanya Admin yang berhak memperbarui banner slider.'], 403);
        }

        $slider = Slider::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['description', 'sort_order', 'is_active']);
        if ($request->has('title')) {
            $data['title'] = !empty(trim($request->title ?? ''))
                ? trim($request->title)
                : $slider->title;
        }
        if ($request->filled('image_path')) {
            $data['image_path'] = $this->processImagePath($request->image_path);
        }

        $slider->update($data);
        AuditLog::log('update', 'Slider', $slider->id);

        return response()->json([
            'message' => 'Banner slider berhasil diperbarui.',
            'slider' => $slider,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak. Hanya Admin yang berhak menghapus banner slider.'], 403);
        }

        $slider = Slider::findOrFail($id);
        $slider->delete();
        AuditLog::log('delete', 'Slider', $id);

        return response()->json(['message' => 'Banner slider berhasil dihapus.']);
    }
}

