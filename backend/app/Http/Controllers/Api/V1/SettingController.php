<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Hanya Admin yang berhak memperbarui pengaturan.'], 403);
        }

        $data = $request->all();
        foreach ($data as $key => $val) {
            Setting::set($key, is_array($val) ? json_encode($val) : $val);
        }

        AuditLog::log('update_settings', 'Setting', null, null, $data);

        return response()->json([
            'message' => 'Pengaturan sistem berhasil disimpan.',
            'settings' => Setting::all()->pluck('value', 'key'),
        ]);
    }
}

