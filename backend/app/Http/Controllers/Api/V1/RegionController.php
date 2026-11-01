<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AuditLog;
use App\Models\Region;
use App\Models\User;
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
        ]);

        $assignment = Assignment::create($request->all());

        // Create in-app notification for the assigned user
        $region = Region::find($request->region_id);
        $assignedUser = User::find($request->user_id);
        if ($assignedUser) {
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'user_id' => $assignedUser->id,
                'type' => 'assignment_priority',
                'data' => json_encode([
                    'title' => 'Prioritas Tugas: Penugasan Baru!',
                    'message' => "Anda telah ditetapkan sebagai penanggung jawab prioritas di pos {$assignment->role_in_post} (" . ($region->name ?? 'Wilayah') . ").",
                    'region_id' => $assignment->region_id,
                    'assignment_id' => $assignment->id,
                    'role_in_post' => $assignment->role_in_post,
                    'region_name' => $region->name ?? 'Wilayah',
                    'url' => '/regions',
                    'created_at' => now()->toIso8601String(),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Penugasan berhasil disimpan dan notifikasi prioritas telah dikirimkan ke anggota.',
            'assignment' => $assignment->load(['region', 'user']),
        ], 201);
    }

    public function destroyAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->delete();
        return response()->json(['message' => 'Penugasan berhasil dihapus.']);
    }
}

