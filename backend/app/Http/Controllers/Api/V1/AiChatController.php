<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    /**
     * Public AI Chatbot endpoint (Adaptive Gemini AI + Local Knowledge Fallback).
     */
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:2000',
            'history' => 'nullable|array',
        ]);

        $userMessage = trim($validated['message']);
        $history = $validated['history'] ?? [];

        // 1. Gather live application context from Settings
        $appName = Setting::get('app_name', 'Media Rojabiyyah 2026');
        $proyektorRaw = Setting::get('proyektor_teams_data');
        $proyektorTeams = $proyektorRaw ? json_decode($proyektorRaw, true) : [];
        $gdriveUrl = Setting::get('gdrive_folder_url', 'https://drive.google.com/drive/folders/1l91WsFBV_sZ89DlvzvSXlgedX0pOwsfa?usp=sharing');

        $titikInfo = "";
        if (!empty($proyektorTeams)) {
            foreach ($proyektorTeams as $t) {
                $titik = $t['titik'] ?? 'Titik Pos';
                $korwil = $t['korwil'] ?? '-';
                $kabel = $t['kabel'] ?? '-';
                $titikInfo .= "- {$titik}: Korwil {$korwil}, Petugas Kabel {$kabel}\n";
            }
        } else {
            $titikInfo = "- Titik A: Korwil Alfani, Kabel Habib (Area Panggung Utama)\n- Titik B: KJA Center\n- Titik C, D, E, F: Zona Sayap & Halaman Luar\n";
        }

        // 2. Check for Gemini API Key
        $apiKey = Setting::get('gemini_api_key') ?: env('GEMINI_API_KEY');

        // Dynamic FAQs from settings
        $savedFaqs = Setting::get('chatbot_faqs');
        $faqContext = '';
        if (!empty($savedFaqs)) {
            $decodedFaqs = json_decode($savedFaqs, true);
            if (is_array($decodedFaqs) && count($decodedFaqs) > 0) {
                $faqContext = "\n7. FAQ & Panduan Khusus Tambahan yang Ditetapkan Pengelola:\n";
                foreach ($decodedFaqs as $df) {
                    $fq = $df['q'] ?? '';
                    $fa = $df['a'] ?? '';
                    if (!empty($fq) && !empty($fa)) {
                        $faqContext .= "- Pertanyaan: {$fq}\n  Jawaban SOP: {$fa}\n";
                    }
                }
            }
        }

        if (!empty($apiKey)) {
            try {
                $systemInstruction = "Kamu adalah Asisten AI Media Rojabiyyah 2026 (Haul Ke-5 & Harlah Rojabiyyah Pondok Pesantren Al-Muhibbin Tambakberas Jombang).
Karakter: Ramah, santun, bernuansa Islami (menggunakan salam/sapaan Islami yang hangat), komunikatif, dan sangat solutif.

PENGETAHUAN KHUSUS APLIKASI & ACARA:
1. Pemasangan & Masalah Proyektor:
   - SOP: Colok kabel power listrik, pasang kabel HDMI yang tersambung ke Extender Fiber Optic (FO)/LAN dari posko utama, nyalakan switch proyektor, pilih input source HDMI 1/2, sesuaikan fokus lensa.
   - Troubleshooting 'No Signal': Pastikan lampu receiver FO menyala (indikator Link/Data aktif), cek kabel HDMI kencang, ganti source input ke HDMI yang sesuai. Jangan cabut colokan listrik jika proyektor mati mendadak (biarkan kipas mendinginkan lampu).
2. Data Titik Pos Proyektor (Live):
{$titikInfo}
3. Tim Dokumentasi & Shooting:
   - Fotografer: Menangkap momen khidmat, candid jamaah, para habaib/kyai.
   - Videografer: Dokumenter dan konten reels media sosial.
   - Kameramen Multicam: Standby di pos kamera masing-masing untuk relay layar proyektor dan live streaming.
   - Jadwal penugasan: Shift hari Rabu (persiapan & tawasul) dan Kamis (puncak haul).
4. Pendaftaran Anggota/Relawan: Terbuka untuk umum di /register-member (termasuk pendataan ukuran seragam panitia) tanpa wajib punya akun login.
5. Google Drive Resmi: Berisi logo resmi, denah proyektor, dan berkas juknis ({$gdriveUrl}).
6. Kritik & Saran: Pengunjung dan panitia dapat memberikan kritik, saran, maupun evaluasi langsung di tab Kritik & Saran aplikasi.{$faqContext}

PERAN UMUM (PENTING):
Selain informasi khusus acara di atas, kamu dapat dan sangat dipersilakan untuk menjawab pertanyaan UMUM apa pun dari pengguna (misalnya tips teknik fotografi, pengaturan ISO/shutter speed kamera, kabel audio/mixer, etika/adab bermajelis, doa, translate bahasa, maupun pengetahuan umum lainnya). Berikan jawaban yang cerdas, terstruktur dengan poin-poin yang mudah dipahami, dan tetap santun.";

                // Build contents payload
                $contents = [];

                // Inject recent history (up to last 6 messages)
                if (is_array($history) && count($history) > 0) {
                    $recent = array_slice($history, -6);
                    foreach ($recent as $item) {
                        $role = ($item['role'] ?? '') === 'user' ? 'user' : 'model';
                        $text = trim($item['text'] ?? '');
                        if (!empty($text)) {
                            $contents[] = [
                                'role' => $role,
                                'parts' => [['text' => $text]],
                            ];
                        }
                    }
                }

                // Add current message
                $contents[] = [
                    'role' => 'user',
                    'parts' => [['text' => $userMessage]],
                ];

                // Models & versions to try
                $savedModel = Setting::get('gemini_model', 'gemini-1.5-flash');
                $savedVer = Setting::get('gemini_api_version', 'v1beta');

                $modelsToTry = array_unique([
                    $savedModel,
                    'gemini-1.5-flash',
                    'gemini-1.5-flash-latest',
                    'gemini-2.0-flash',
                    'gemini-1.5-pro',
                    'gemini-pro',
                ]);

                $versionsToTry = array_unique([$savedVer, 'v1beta', 'v1']);

                foreach ($modelsToTry as $model) {
                    foreach ($versionsToTry as $ver) {
                        $url = "https://generativelanguage.googleapis.com/{$ver}/models/{$model}:generateContent?key={$apiKey}";

                        // 1. Try with system_instruction
                        $payload = [
                            'system_instruction' => [
                                'parts' => [['text' => $systemInstruction]],
                            ],
                            'contents' => $contents,
                            'generationConfig' => [
                                'temperature' => 0.7,
                                'maxOutputTokens' => 800,
                            ],
                        ];

                        $response = Http::timeout(12)
                            ->withHeaders(['Content-Type' => 'application/json'])
                            ->post($url, $payload);

                        // 2. If system_instruction is not supported (400), try fallback payload
                        if ($response->status() === 400 && str_contains($response->body(), 'system_instruction')) {
                            $fallbackContents = $contents;
                            if (!empty($fallbackContents)) {
                                $fallbackContents[0]['parts'][0]['text'] = "[INSTRUKSI SISTEM: {$systemInstruction}]\n\n" . $fallbackContents[0]['parts'][0]['text'];
                            }
                            $response = Http::timeout(12)
                                ->withHeaders(['Content-Type' => 'application/json'])
                                ->post($url, [
                                    'contents' => $fallbackContents,
                                    'generationConfig' => [
                                        'temperature' => 0.7,
                                        'maxOutputTokens' => 800,
                                    ],
                                ]);
                        }

                        if ($response->successful()) {
                            $json = $response->json();
                            $replyText = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;

                            if (!empty($replyText)) {
                                // Save working model/version for fast future calls
                                if ($model !== $savedModel || $ver !== $savedVer) {
                                    Setting::set('gemini_model', $model);
                                    Setting::set('gemini_api_version', $ver);
                                }

                                return response()->json([
                                    'reply' => $replyText,
                                    'provider' => 'gemini',
                                    'model' => $model,
                                ]);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Gemini API exception: ' . $e->getMessage());
            }
        }

        // 3. Fallback to intelligent local AI knowledge engine
        $fallback = $this->getLocalAiResponse($userMessage, $titikInfo);

        return response()->json([
            'reply' => $fallback['text'],
            'provider' => 'local_ai',
            'action' => $fallback['action'] ?? null,
        ]);
    }

    /**
     * Test Gemini API Connection & Auto-Discover Working Model (Super Admin setting).
     */
    public function testGemini(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
        ]);

        $apiKey = trim($request->api_key);

        // 1. Discover available models for this specific API key via ListModels
        $discoveredModels = [];
        $versionsToTry = ['v1beta', 'v1'];

        foreach ($versionsToTry as $ver) {
            try {
                $listRes = Http::timeout(8)
                    ->get("https://generativelanguage.googleapis.com/{$ver}/models?key={$apiKey}");

                if ($listRes->successful()) {
                    $modelsData = $listRes->json()['models'] ?? [];
                    foreach ($modelsData as $m) {
                        $methods = $m['supportedGenerationMethods'] ?? [];
                        if (in_array('generateContent', $methods)) {
                            $name = str_replace('models/', '', $m['name']);
                            $discoveredModels[] = $name;
                        }
                    }
                    if (!empty($discoveredModels)) {
                        break;
                    }
                }
            } catch (\Exception $e) {
                // Continue
            }
        }

        // Preferred priority order
        $priority = [
            'gemini-1.5-flash',
            'gemini-1.5-flash-latest',
            'gemini-1.5-flash-8b',
            'gemini-2.0-flash',
            'gemini-2.0-flash-exp',
            'gemini-1.5-pro',
            'gemini-1.5-pro-latest',
            'gemini-pro',
        ];

        $modelsToTry = [];
        if (!empty($discoveredModels)) {
            foreach ($priority as $p) {
                if (in_array($p, $discoveredModels)) {
                    $modelsToTry[] = $p;
                }
            }
            // Append any other discovered models
            foreach ($discoveredModels as $dm) {
                if (!in_array($dm, $modelsToTry)) {
                    $modelsToTry[] = $dm;
                }
            }
        } else {
            $modelsToTry = $priority;
        }

        $lastError = 'Tidak ada model yang merespon.';

        foreach ($modelsToTry as $model) {
            foreach ($versionsToTry as $ver) {
                try {
                    $url = "https://generativelanguage.googleapis.com/{$ver}/models/{$model}:generateContent?key={$apiKey}";
                    $res = Http::timeout(10)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, [
                            'contents' => [
                                [
                                    'role' => 'user',
                                    'parts' => [['text' => 'Halo Gemini, konfirmasi dalam satu kalimat pendek bahwa API aktif.']],
                                ],
                            ],
                        ]);

                    if ($res->successful()) {
                        $json = $res->json();
                        $reply = $json['candidates'][0]['content']['parts'][0]['text'] ?? 'Koneksi Berhasil.';

                        // Save key and working model & version
                        Setting::set('gemini_api_key', $apiKey);
                        Setting::set('gemini_model', $model);
                        Setting::set('gemini_api_version', $ver);

                        return response()->json([
                            'success' => true,
                            'message' => "Alhamdulillah, koneksi Google Gemini AI berhasil terhubung menggunakan model [{$model}] ({$ver})!",
                            'model' => $model,
                            'sample_reply' => trim($reply),
                        ]);
                    } else {
                        $lastError = $res->json()['error']['message'] ?? $res->body();
                    }
                } catch (\Exception $e) {
                    $lastError = $e->getMessage();
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghubungkan Gemini API: ' . $lastError,
        ], 400);
    }

    /**
     * Intelligent local fallback knowledge engine.
     */
    private function getLocalAiResponse(string $query, string $titikInfo): array
    {
        $q = strtolower($query);

        if (str_contains($q, 'kritik') || str_contains($q, 'saran') || str_contains($q, 'masukan')) {
            return [
                'text' => "Masukan dan evaluasi dari Anda sangat berharga bagi kesempurnaan acara Haul Ke-5 & Rojabiyyah 2026.\n\nSilakan klik tombol di bawah untuk langsung membuka formulir **Kritik & Saran**:",
                'action' => ['type' => 'feedback'],
            ];
        }

        if (str_contains($q, 'drive') || str_contains($q, 'berkas') || str_contains($q, 'download') || str_contains($q, 'logo')) {
            return [
                'text' => "Seluruh berkas resmi (Logo RJB resolusi tinggi, rundown, skema jalur kabel FO, denah posko) dapat Anda akses langsung di Google Drive Resmi.\n\nKlik tombol di bawah untuk membuka:",
                'action' => ['type' => 'drive'],
            ];
        }

        if (str_contains($q, 'pasang') || (str_contains($q, 'proyektor') && str_contains($q, 'cara'))) {
            return [
                'text' => "**SOP Pemasangan Proyektor & Layar:**\n\n1. **Penempatan Pos**: Letakkan di tempat datar, kokoh, dan aman dari kerumunan jamaah.\n2. **Kabel Power**: Sambungkan ke stopkontak listrik yang stabil.\n3. **Kabel Sinyal**: Pasang kabel HDMI ke unit proyektor dari receiver Extender FO/LAN.\n4. **Nyalakan**: Tekan tombol Power dan tunggu lampu indikator berubah dari merah ke hijau/biru.\n5. **Pilih Source**: Atur input ke HDMI 1 atau HDMI 2 sesuai colokan kabel.\n6. **Fokus Lensa**: Putar ring lensa hingga teks terlihat tajam.",
            ];
        }

        if (str_contains($q, 'no signal') || str_contains($q, 'sinyal') || str_contains($q, 'mati') || str_contains($q, 'kedip')) {
            return [
                'text' => "**Panduan Cepat Mengatasi Kendala Proyektor:**\n\n• **Jika 'No Signal':**\n  1. Cek port input, pastikan source terpilih sesuai colokan HDMI yang aktif.\n  2. Periksa lampu indikator Link/Data pada receiver Fiber Optic. Jika mati, cek adaptor listrik receiver.\n  3. Pastikan kabel FO tidak terlipat tajam atau terinjak jamaah.\n\n• **Jika Proyektor Mati Mendadak:**\n  1. Jangan cabut colokan listriknya (biarkan kipas mendinginkan bohlam).\n  2. Tunggu 5-10 menit, lalu nyalakan kembali.\n  3. Hubungi Korwil Pos atau Tim Kabel jika kendala berlanjut.",
                'action' => ['type' => 'faq'],
            ];
        }

        if (str_contains($q, 'titik') || str_contains($q, 'korwil') || str_contains($q, 'kabel')) {
            return [
                'text' => "**Pembagian Titik Pos Proyektor (A s/d F):**\n\n{$titikInfo}\nSeluruh sinyal video disiarkan terpusat dari server live streaming utama.",
            ];
        }

        if (str_contains($q, 'dokumentasi') || str_contains($q, 'foto') || str_contains($q, 'video') || str_contains($q, 'shooting')) {
            return [
                'text' => "**SOP Tim Dokumentasi & Shooting Haul 2026:**\n\n• **Fotografer**: Mengabadikan momen khidmat, candid jamaah, para habaib & kyai.\n• **Videografer Liputan**: Pengambilan video cinematic, teaser, dan reel media sosial.\n• **Kameramen Multicam Live**: Standby di pos kamera tripod untuk relay proyektor dan siaran langsung.\n• **Jadwal Kerja**: Shift Rabu (persiapan & tawasul) dan Kamis (puncak acara haul).",
                'action' => ['type' => 'faq'],
            ];
        }

        return [
            'text' => "Terima kasih atas pertanyaannya! Asisten AI siap membantu Anda seputar **Pemasangan & Kendala Proyektor**, **Titik Pos A-F & Korwil**, **Tugas Liputan Dokumentasi**, maupun pertanyaan umum lainnya.\n\nSilakan pilih menu cepat di bawah atau tanyakan apa saja yang ingin Anda ketahui.",
            'action' => ['type' => 'faq'],
        ];
    }
}
