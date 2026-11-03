<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Public read-only settings with strict key whitelisting.
     * Sensitive secrets (API keys, WA tokens, credentials) are strictly NEVER exposed.
     */
    public function publicSettings()
    {
        $allowedKeys = [
            'app_title',
            'logo_url',
            'kop_name',
            'kop_sub_theme',
            'kop_logo_url',
            'sub_theme',
            'hero_title_white',
            'hero_title_gradient',
            'hero_subtitle',
            'hero_desc',
            'gdrive_folder_url',
            'gdrive_preview_embed',
            'gdrive_embed_active',
            'chatbot_faqs',
            'operational_regions',
            'operational_partners',
            'proyektor_teams',
            'proyektor_sop',
            'jadwal_tim',
            'jadwal_shooting',
            'jadwal_instruksi',
            'calendar_events',
            'denah_photos',
            'custom_fields',
        ];

        $settings = Setting::whereIn('key', $allowedKeys)->pluck('value', 'key');
        return response()->json($settings);
    }

    /**
     * Admin internal settings.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

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

