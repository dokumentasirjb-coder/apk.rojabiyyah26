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

    public function testWhatsApp(Request $request)
    {
        $user = $request->user();
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Hanya Admin yang berhak melakukan tes WhatsApp.'], 403);
        }

        $request->validate([
            'phone' => 'required|string',
            'token' => 'nullable|string',
            'provider' => 'nullable|string',
        ]);

        if ($request->filled('token')) {
            Setting::set('wa_gateway_token', $request->token);
        }
        if ($request->filled('provider')) {
            Setting::set('wa_gateway_provider', $request->provider);
        }

        $targetPhone = $request->phone;
        $testMsg = "*TES INTEGRASI WHATSAPP GATEWAY*\n"
                 . "*PANITIA ROJABIYYAH & HAUL 2026*\n"
                 . "_Pondok Pesantren Al-Muhibbin Tambakberas_\n\n"
                 . "Assalamu'alaikum Wr. Wb.\n"
                 . "Halo, ini adalah pesan verifikasi dari Sistem Administrasi Haul 2026.\n\n"
                 . "Gateway WhatsApp otomatis Anda telah *TERHUBUNG DENGAN SUKSES*! ✅\n"
                 . "Sistem akan otomatis mengirimkan pesan penugasan anggota tanpa perlu klik manual:\n"
                 . "1️⃣ Saat Admin menetapkan tugas\n"
                 . "2️⃣ Saat H-10 menit sebelum jam tugas\n"
                 . "3️⃣ Saat waktu tugas resmi dimulai\n\n"
                 . "Waktu Verifikasi: " . now()->format('d M Y H:i:s') . " WIB\n"
                 . "Semangat berkhidmah! 🤲";

        $res = \App\Services\WhatsAppService::send($targetPhone, $testMsg);

        return response()->json([
            'message' => $res['message'],
            'result' => $res,
        ], $res['success'] ? 200 : 400);
    }
}

