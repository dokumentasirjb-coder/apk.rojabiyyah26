<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Clean and normalize phone number into standard Indonesian WhatsApp format (628xxx).
     */
    public static function formatPhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $cleaned = preg_replace('/[^\d]/', '', $phone);
        if (empty($cleaned)) {
            return null;
        }

        if (str_starts_with($cleaned, '08')) {
            $cleaned = '628' . substr($cleaned, 2);
        } elseif (str_starts_with($cleaned, '8')) {
            $cleaned = '628' . substr($cleaned, 1);
        } elseif (!str_starts_with($cleaned, '62') && strlen($cleaned) >= 9) {
            $cleaned = '62' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Check if automated WhatsApp Gateway is configured and enabled.
     */
    public static function isConfigured(): bool
    {
        $enabled = Setting::get('wa_gateway_enabled', '0');
        $token = Setting::get('wa_gateway_token', '');

        return ($enabled === '1' || $enabled === 'true' || $enabled === true) && !empty(trim((string)$token));
    }

    /**
     * Send WhatsApp message automatically via configured Gateway API.
     *
     * @param string $phone Target phone number
     * @param string $message Formatted text message
     * @return array [success => bool, message => string, error => mixed]
     */
    public static function send(string $phone, string $message): array
    {
        $cleanPhone = self::formatPhone($phone);
        if (!$cleanPhone) {
            return [
                'success' => false,
                'message' => 'Nomor telepon tujuan tidak valid.',
            ];
        }

        $token = trim((string) Setting::get('wa_gateway_token', ''));
        $provider = strtolower(trim((string) Setting::get('wa_gateway_provider', 'fonnte')));
        $customUrl = trim((string) Setting::get('wa_gateway_url', ''));

        // If not configured, gracefully return info without breaking application
        if (empty($token)) {
            Log::info("WhatsApp Gateway skipped (no token configured). Target: {$cleanPhone}");
            return [
                'success' => false,
                'skipped' => true,
                'message' => 'Token WhatsApp Gateway belum diatur di menu Pengaturan. Pesan disimpan di lonceng notifikasi aplikasi.',
            ];
        }

        try {
            if ($provider === 'fonnte') {
                $endpoint = !empty($customUrl) ? $customUrl : 'https://api.fonnte.com/send';
                $response = Http::timeout(15)
                    ->withHeaders([
                        'Authorization' => $token,
                    ])
                    ->post($endpoint, [
                        'target' => $cleanPhone,
                        'message' => $message,
                        'countryCode' => '62',
                    ]);

                $resData = $response->json();
                $isSuccess = $response->successful() && (($resData['status'] ?? false) == true);

                Log::info("WhatsApp Sent via Fonnte to {$cleanPhone}: " . ($isSuccess ? 'SUCCESS' : 'FAILED'), [
                    'response' => $resData,
                ]);

                return [
                    'success' => $isSuccess,
                    'provider' => 'fonnte',
                    'message' => $isSuccess ? 'Pesan WhatsApp otomatis berhasil dikirim via Fonnte.' : ($resData['reason'] ?? 'Gagal kirim via Fonnte.'),
                    'response' => $resData,
                ];
            } elseif ($provider === 'wablas') {
                $endpoint = !empty($customUrl) ? $customUrl : 'https://pati.wablas.com/api/send-message';
                $response = Http::timeout(15)
                    ->withHeaders([
                        'Authorization' => $token,
                    ])
                    ->post($endpoint, [
                        'phone' => $cleanPhone,
                        'message' => $message,
                    ]);

                $resData = $response->json();
                $isSuccess = $response->successful() && (($resData['status'] ?? '') === 'success');

                Log::info("WhatsApp Sent via Wablas to {$cleanPhone}: " . ($isSuccess ? 'SUCCESS' : 'FAILED'), [
                    'response' => $resData,
                ]);

                return [
                    'success' => $isSuccess,
                    'provider' => 'wablas',
                    'message' => $isSuccess ? 'Pesan WhatsApp otomatis berhasil dikirim via Wablas.' : ($resData['message'] ?? 'Gagal kirim via Wablas.'),
                    'response' => $resData,
                ];
            } else {
                // Custom Webhook Gateway
                $endpoint = !empty($customUrl) ? $customUrl : 'https://api.fonnte.com/send';
                $response = Http::timeout(15)
                    ->withHeaders([
                        'Authorization' => $token,
                        'Content-Type' => 'application/json',
                    ])
                    ->post($endpoint, [
                        'phone' => $cleanPhone,
                        'target' => $cleanPhone,
                        'message' => $message,
                    ]);

                $isSuccess = $response->successful();
                Log::info("WhatsApp Sent via Custom Gateway to {$cleanPhone}: " . ($isSuccess ? 'SUCCESS' : 'FAILED'));

                return [
                    'success' => $isSuccess,
                    'provider' => 'custom',
                    'message' => $isSuccess ? 'Pesan WhatsApp otomatis berhasil dikirim.' : 'Gagal kirim via Gateway kustom.',
                ];
            }
        } catch (\Throwable $e) {
            Log::error("WhatsApp Gateway Exception to {$cleanPhone}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kendala jaringan ke WhatsApp Gateway: ' . $e->getMessage(),
            ];
        }
    }
}

