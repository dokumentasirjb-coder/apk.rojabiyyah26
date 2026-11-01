<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\FieldReport;
use App\Models\ReportFollowup;
use App\Models\ReportMedia;
use Illuminate\Http\Request;

class FieldReportController extends Controller
{
    public function index(Request $request)
    {
        $query = FieldReport::with(['region', 'reporter.member.division', 'media', 'followups.user']);

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('followup_status')) {
            $query->where('followup_status', $request->followup_status);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate($request->input('per_page', 20));

        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'status' => 'required|in:normal,perlu_perhatian,rusak_kendala',
            'description' => 'required|string',
            'media' => 'nullable|array',
            'media.*.path' => 'required|string',
            'media.*.type' => 'nullable|in:photo,video',
        ]);

        $user = $request->user();

        $report = FieldReport::create([
            'region_id' => $request->region_id,
            'reporter_id' => $user->id,
            'status' => $request->status,
            'followup_status' => $request->status === 'normal' ? 'resolved' : 'open',
            'description' => $request->description,
        ]);

        if ($request->has('media')) {
            foreach ($request->media as $m) {
                ReportMedia::create([
                    'report_id' => $report->id,
                    'path' => $m['path'],
                    'type' => $m['type'] ?? 'photo',
                ]);
            }
        }

        AuditLog::log('create', 'FieldReport', $report->id, null, $report->toArray());

        return response()->json([
            'message' => 'Laporan lapangan berhasil dikirim.',
            'report' => $report->load(['region', 'reporter', 'media']),
        ], 201);
    }

    public function show($id)
    {
        $report = FieldReport::with(['region', 'reporter', 'media', 'followups.user'])->findOrFail($id);
        return response()->json($report);
    }

    public function addFollowup(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
            'solution' => 'required|string',
        ]);

        $user = $request->user();
        $report = FieldReport::findOrFail($id);

        $followup = ReportFollowup::create([
            'report_id' => $report->id,
            'user_id' => $user->id,
            'status' => $request->status,
            'solution' => $request->solution,
        ]);

        $report->update(['followup_status' => $request->status]);

        AuditLog::log('followup_report', 'FieldReport', $report->id, null, ['status' => $request->status]);

        return response()->json([
            'message' => 'Tindak lanjut laporan berhasil disimpan.',
            'report' => $report->fresh(['region', 'reporter', 'media', 'followups.user']),
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'followup_status' => 'required|in:open,in_progress,resolved',
        ]);

        $report = FieldReport::findOrFail($id);
        $report->update(['followup_status' => $request->followup_status]);

        return response()->json([
            'message' => 'Status laporan berhasil diperbarui.',
            'report' => $report,
        ]);
    }
}

