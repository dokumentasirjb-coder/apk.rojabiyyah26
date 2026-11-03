<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Store feedback from public (guest / panitia / jamaah).
     */
    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:3|max:3000',
            'name' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:50',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $feedback = Feedback::create([
            'name' => $validated['name'] ?: 'Anonim / Jamaah',
            'contact' => $validated['contact'] ?? null,
            'category' => $validated['category'] ?: 'Umum',
            'message' => $validated['message'],
            'rating' => $validated['rating'] ?? null,
            'status' => 'unread',
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Terima kasih atas kritik dan saran yang Anda berikan. Masukan Anda sangat berarti bagi kelancaran dan perbaikan acara!',
            'data' => $feedback,
        ], 201);
    }

    /**
     * Admin: List all feedbacks.
     */
    public function index(Request $request)
    {
        $query = Feedback::latest();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $feedbacks = $query->paginate($request->input('per_page', 20));

        return response()->json($feedbacks);
    }

    /**
     * Admin: Mark feedback as read.
     */
    public function markAsRead($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update(['status' => 'read']);

        return response()->json([
            'message' => 'Status kritik & saran diperbarui menjadi dibaca.',
            'data' => $feedback,
        ]);
    }

    /**
     * Admin: Delete feedback.
     */
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json(['message' => 'Kritik & saran berhasil dihapus.']);
    }
}
