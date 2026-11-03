<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AuditLog;
use App\Models\Region;
use App\Models\User;
use App\Services\AssignmentReminderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::with(['picUser', 'assignments.user'])->withCount('fieldReports')->get();
        return response()->json($regions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:regions,name',
            'code' => 'nullable|string|max:50',
            'pic_user_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $region = Region::create($request->all());
        AuditLog::log('create', 'Region', $region->id, null, $region->toArray());

        return response()->json([
            'message' => 'Posko/Wilayah berhasil ditambahkan.',
            'region' => $region->load('picUser'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $region = Region::findOrFail($id);
        $request->validate([
            'name' => 'required|string|unique:regions,name,' . $region->id,
            'code' => 'nullable|string|max:50',
            'pic_user_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $region->update($request->all());
        AuditLog::log('update', 'Region', $region->id);

        return response()->json([
            'message' => 'Posko berhasil diperbarui.',
            'region' => $region->load('picUser'),
        ]);
    }

    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        $region->delete();
        AuditLog::log('delete', 'Region', $id);

        return response()->json(['message' => 'Posko berhasil dihapus.']);
    }

    public function assignments(Request $request)
    {
        // Trigger automated reminder checks in the background (H-10m and H-0)
        AssignmentReminderService::checkAndSendReminders();

        $query = Assignment::with(['region', 'user.member.division']);

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return response()->json($query->get());
    }

    public function regionAssignments($id)
    {
        AssignmentReminderService::checkAndSendReminders();
        $assignments = Assignment::where('region_id', $id)->with(['region', 'user.member.division'])->get();
        return response()->json($assignments);
    }

    public function storeRegionAssignment(Request $request, $id)
    {
        $request->merge(['region_id' => $id]);
        return $this->storeAssignment($request);
    }

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'user_id' => 'required|exists:users,id',
            'role_in_post' => 'required|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date',
            'duty_name' => 'nullable|string',
            'category' => 'nullable|string',
            'execution_time' => 'nullable|string',
            'specific_location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $data = $request->only([
            'region_id',
            'user_id',
            'role_in_post',
            'duty_name',
            'category',
            'execution_time',
            'specific_location',
            'description',
            'start_at',
            'end_at',
        ]);

        if (empty($data['duty_name'])) {
            $data['duty_name'] = $request->role_in_post;
        }

        // Parse start_at from execution_date and execution_time if start_at not provided explicitly
        if (empty($data['start_at']) && $request->filled('execution_date')) {
            $date = $request->execution_date;
            $time = '08:00:00';
            if ($request->filled('execution_time') && preg_match('/(\d{1,2})[:.](\d{2})/', $request->execution_time, $matches)) {
                $time = sprintf('%02d:%02d:00', (int)$matches[1], (int)$matches[2]);
            }
            $data['start_at'] = "{$date} {$time}";
        }

        $assignment = Assignment::create($data);

        // Notifikasi #1: Kirimkan saat itu juga secara otomatis (In-App + WhatsApp Gateway)
        $notifResult = AssignmentReminderService::sendAssignedNotification($assignment);

        return response()->json([
            'message' => 'Penugasan berhasil disimpan dan notifikasi otomatis (In-App & WhatsApp) telah diproses.',
            'assignment' => $assignment->load(['region', 'user.member']),
            'notif_status' => $notifResult,
        ], 201);
    }

    public function checkReminders()
    {
        $result = AssignmentReminderService::checkAndSendReminders();
        return response()->json([
            'message' => 'Pemeriksaan pengingat tugas berhasil dijalankan.',
            'data' => $result,
        ]);
    }

    public function destroyAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->delete();
        return response()->json(['message' => 'Penugasan berhasil dihapus.']);
    }
}

