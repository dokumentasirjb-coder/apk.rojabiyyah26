<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of divisions with member count.
     */
    public function index()
    {
        $divisions = Division::withCount('members')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($divisions);
    }

    /**
     * Store a newly created division.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:divisions,name',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama divisi wajib diisi.',
            'name.unique' => 'Nama divisi sudah ada di sistem.',
            'name.max' => 'Nama divisi maksimal 150 karakter.',
        ]);

        $division = Division::create($validated);
        $division->loadCount('members');

        AuditLog::log('create', 'Division', $division->id, null, $division->toArray());

        return response()->json([
            'message' => 'Divisi kepanitiaan berhasil ditambahkan.',
            'division' => $division,
        ], 201);
    }

    /**
     * Display the specified division with its members.
     */
    public function show($id)
    {
        $division = Division::with(['members.user'])->withCount('members')->findOrFail($id);
        return response()->json($division);
    }

    /**
     * Update the specified division.
     */
    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama divisi wajib diisi.',
            'name.unique' => 'Nama divisi sudah digunakan oleh divisi lain.',
            'name.max' => 'Nama divisi maksimal 150 karakter.',
        ]);

        $old = $division->toArray();
        $division->update($validated);
        $division->loadCount('members');

        AuditLog::log('update', 'Division', $division->id, $old, $division->toArray());

        return response()->json([
            'message' => 'Divisi kepanitiaan berhasil diperbarui.',
            'division' => $division,
        ]);
    }

    /**
     * Remove the specified division.
     */
    public function destroy($id)
    {
        $division = Division::withCount('members')->findOrFail($id);

        $memberCount = $division->members_count;
        $name = $division->name;

        // On delete set null sudah ada di database, tapi kita catat log
        $division->delete();

        AuditLog::log('delete', 'Division', (int)$id, ['name' => $name, 'member_count' => $memberCount], null);

        return response()->json([
            'message' => "Divisi \"{$name}\" berhasil dihapus.",
            'affected_members' => $memberCount,
        ]);
    }
}

