<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AiChatController;
use App\Http\Controllers\Api\V1\AuditLogController;
use App\Http\Controllers\Api\V1\CashController;
use App\Http\Controllers\Api\V1\DangerZoneController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\DivisionController;
use App\Http\Controllers\Api\V1\FeedbackController;
use App\Http\Controllers\Api\V1\FieldReportController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\LoanController;
use App\Http\Controllers\Api\V1\MemberController;
use App\Http\Controllers\Api\V1\PublicEventController;
use App\Http\Controllers\Api\V1\RegionController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\SliderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', fn () => response()->json(['status' => 'ok', 'app' => 'Administrasi Rojabiyyah 2026']));
    // Public routes (Read-only for guests with rate limiting)
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/public/overview', [PublicEventController::class, 'overview'])->middleware('throttle:60,1');
    Route::get('/public/landing', [PublicEventController::class, 'overview'])->middleware('throttle:60,1');
    Route::get('/public/sliders', [SliderController::class, 'publicSliders'])->middleware('throttle:60,1');
    Route::get('/public/reports', [FieldReportController::class, 'publicReports'])->middleware('throttle:60,1');
    Route::get('/public/loans/verify/{code}', [LoanController::class, 'verifyPublic'])->middleware('throttle:30,1');
    Route::get('/settings/public', [SettingController::class, 'publicSettings'])->middleware('throttle:60,1');
    Route::get('/public/options', [MemberController::class, 'publicOptions'])->middleware('throttle:60,1');
    Route::post('/public/register-member', [MemberController::class, 'publicRegister'])->middleware('throttle:10,1');
    Route::post('/public/register-account', [MemberController::class, 'publicRegisterAccount'])->middleware('throttle:10,1');
    Route::post('/public/feedback', [FeedbackController::class, 'publicStore'])->middleware('throttle:15,1');
    Route::post('/public/ai-chat', [AiChatController::class, 'chat'])->middleware('throttle:30,1');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Global Rate Limiting for Authenticated Endpoints (60 req/min)
        Route::middleware('throttle:60,1')->group(function () {
            // Auth & Profile (All authenticated users)
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/password/change', [AuthController::class, 'changePassword']);

            // Dashboard Stats
            Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

            // Read-only Shared Catalogues for Authenticated Users
            Route::get('/members', [MemberController::class, 'index']);
            Route::get('/members/{id}', [MemberController::class, 'show']);
            Route::get('/divisions', [DivisionController::class, 'index']);
            Route::get('/divisions/{id}', [DivisionController::class, 'show']);
            Route::get('/items', [InventoryController::class, 'index']);
            Route::get('/items/{id}', [InventoryController::class, 'show']);
            Route::get('/inventory-categories', [InventoryController::class, 'categories']);
            Route::get('/categories', [InventoryController::class, 'categories']);
            Route::get('/partners', [InventoryController::class, 'partners']);
            Route::get('/regions', [RegionController::class, 'index']);
            Route::get('/regions/{id}/assignments', [RegionController::class, 'regionAssignments']);
            Route::get('/assignments', [RegionController::class, 'assignments']);
            Route::get('/sliders', [SliderController::class, 'index']);

            // Loans & Circulation (Members submit loan, Admins review/handover)
            Route::get('/loans', [LoanController::class, 'index']);
            Route::post('/loans', [LoanController::class, 'store']);
            Route::get('/loans/{id}', [LoanController::class, 'show']);
            Route::post('/loans/{id}/approve', [LoanController::class, 'approve']);
            Route::post('/loans/{id}/reject', [LoanController::class, 'reject']);
            Route::post('/loans/{id}/handover', [LoanController::class, 'handover']);
            Route::post('/loans/{id}/return', [LoanController::class, 'returnLoan']);
            Route::post('/loans/{id}/cancel', [LoanController::class, 'cancel']);
            Route::get('/loans/{id}/pdf', [LoanController::class, 'generatePdf']);

            // Field Reports (Members submit, Admins followup)
            Route::get('/reports', [FieldReportController::class, 'index']);
            Route::post('/reports', [FieldReportController::class, 'store']);
            Route::get('/reports/{id}', [FieldReportController::class, 'show']);
            Route::post('/reports/{id}/followups', [FieldReportController::class, 'addFollowup']);
            Route::patch('/reports/{id}/status', [FieldReportController::class, 'updateStatus']);

            // Notifications
            Route::get('/notifications', function (\Illuminate\Http\Request $request) {
                \App\Services\NotificationService::syncForUser($request->user());

                $notifications = \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('user_id', $request->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(30)
                    ->get()
                    ->map(function ($n) {
                        $n->data = json_decode($n->data, true);
                        return $n;
                    });
                $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('user_id', $request->user()->id)
                    ->whereNull('read_at')
                    ->count();

                return response()->json([
                    'notifications' => $notifications,
                    'unread_count' => $unreadCount,
                ]);
            });
            Route::post('/notifications/mark-read', function (\Illuminate\Http\Request $request) {
                \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('user_id', $request->user()->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => now(), 'updated_at' => now()]);

                return response()->json(['message' => 'Semua notifikasi ditandai dibaca.']);
            });
            Route::post('/notifications/{id}/read', function (\Illuminate\Http\Request $request, $id) {
                \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('user_id', $request->user()->id)
                    ->where('id', $id)
                    ->update(['read_at' => now(), 'updated_at' => now()]);

                return response()->json(['message' => 'Notifikasi ditandai dibaca.']);
            });

            // Cash Management (Khusus Super Admin & Bendahara)
            Route::post('/cash/verify-password', function (\Illuminate\Http\Request $request) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                $request->validate(['password' => 'required|string']);
                $correct = \App\Models\Setting::get('cash_password', env('CASH_DEFAULT_PIN', 'kas2026'));
                if ($request->password === $correct) {
                    return response()->json(['valid' => true, 'message' => 'Akses kas berhasil diverifikasi.']);
                }
                return response()->json(['valid' => false, 'message' => 'Kata sandi salah. Silakan coba lagi.'], 401);
            });
            Route::post('/cash/change-password', function (\Illuminate\Http\Request $request) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                $request->validate(['new_password' => 'required|string|min:4']);
                \App\Models\Setting::set('cash_password', $request->new_password);
                return response()->json(['message' => 'Kata sandi akses kas berhasil diperbarui.']);
            });
            Route::get('/cash/transactions', function (\Illuminate\Http\Request $request) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return app(\App\Http\Controllers\Api\V1\CashController::class)->index($request);
            });
            Route::post('/cash/transactions', function (\Illuminate\Http\Request $request) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return app(\App\Http\Controllers\Api\V1\CashController::class)->store($request);
            });
            Route::put('/cash/transactions/{id}', function (\Illuminate\Http\Request $request, $id) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return app(\App\Http\Controllers\Api\V1\CashController::class)->update($request, $id);
            });
            Route::delete('/cash/transactions/{id}', function (\Illuminate\Http\Request $request, $id) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return app(\App\Http\Controllers\Api\V1\CashController::class)->destroy($request, $id);
            });
            Route::get('/cash/summary', function (\Illuminate\Http\Request $request) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return app(\App\Http\Controllers\Api\V1\CashController::class)->summary($request);
            });
            Route::get('/cash/export', function (\Illuminate\Http\Request $request) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return app(\App\Http\Controllers\Api\V1\CashController::class)->export($request);
            });
            Route::get('/cash/template', function (\Illuminate\Http\Request $request) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return app(\App\Http\Controllers\Api\V1\CashController::class)->template($request);
            });
            Route::post('/cash/import/preview', function (\Illuminate\Http\Request $request) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return app(\App\Http\Controllers\Api\V1\CashController::class)->importPreview($request);
            });
            Route::post('/cash/import', function (\Illuminate\Http\Request $request) {
                if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                    return response()->json(['message' => 'Akses ditolak.'], 403);
                }
                return app(\App\Http\Controllers\Api\V1\CashController::class)->importExecute($request);
            });

            // =========================================================
            // ADMINISTRATIVE MUTATIONS (Super Admin & Admin ONLY)
            // =========================================================
            Route::middleware('role:super_admin,admin')->group(function () {
                // Landing Page Management
                Route::post('/admin/landing/upload-denah', [PublicEventController::class, 'uploadDenah']);
                Route::post('/admin/landing/delete-denah', [PublicEventController::class, 'deleteDenah']);
                Route::post('/admin/landing/upload-jadwal', [PublicEventController::class, 'uploadJadwal']);
                Route::post('/admin/landing/delete-jadwal', [PublicEventController::class, 'deleteJadwal']);
                Route::post('/admin/landing/upload-proyektor-photo', [PublicEventController::class, 'uploadProyektorPhotos']);
                Route::post('/admin/landing/delete-proyektor-photo', [PublicEventController::class, 'deleteProyektorPhoto']);
                Route::post('/admin/landing/save-proyektor', [PublicEventController::class, 'saveProyektorTeams']);
                Route::post('/admin/landing/save-jadwal', [PublicEventController::class, 'saveJadwalTim']);
                Route::post('/admin/landing/save-hero', [PublicEventController::class, 'saveHeroSettings']);
                Route::post('/admin/landing/save-calendar', [PublicEventController::class, 'saveCalendarEvents']);

                // Kritik & Saran Management
                Route::get('/admin/feedbacks', [FeedbackController::class, 'index']);
                Route::patch('/admin/feedbacks/{id}/read', [FeedbackController::class, 'markAsRead']);
                Route::delete('/admin/feedbacks/{id}', [FeedbackController::class, 'destroy']);

                // Google Gemini AI Test & Setup
                Route::post('/admin/ai/test-gemini', [AiChatController::class, 'testGemini']);

                // Members Mutations
                Route::post('/members', [MemberController::class, 'store']);
                Route::get('/members/export', [MemberController::class, 'export']);
                Route::get('/members/template', [MemberController::class, 'template']);
                Route::post('/members/import/preview', [MemberController::class, 'importPreview']);
                Route::post('/members/import', [MemberController::class, 'importExecute']);
                Route::post('/members/{id}/create-account', [MemberController::class, 'createAccount']);
                Route::post('/members/{id}/revoke-account', [MemberController::class, 'revokeAccount']);
                Route::put('/members/{id}', [MemberController::class, 'update']);
                Route::delete('/members/{id}', [MemberController::class, 'destroy']);

                // Divisions Mutations
                Route::post('/divisions', [DivisionController::class, 'store']);
                Route::put('/divisions/{id}', [DivisionController::class, 'update']);
                Route::delete('/divisions/{id}', [DivisionController::class, 'destroy']);

                // Inventory & Categories Mutations
                Route::post('/items', [InventoryController::class, 'store']);
                Route::get('/items/export', [InventoryController::class, 'export']);
                Route::get('/items/template', [InventoryController::class, 'template']);
                Route::post('/items/import/preview', [InventoryController::class, 'importPreview']);
                Route::post('/items/import', [InventoryController::class, 'importExecute']);
                Route::put('/items/{id}', [InventoryController::class, 'update']);
                Route::delete('/items/{id}', [InventoryController::class, 'destroy']);
                Route::post('/inventory-categories', [InventoryController::class, 'storeCategory']);
                Route::delete('/inventory-categories/{id}', [InventoryController::class, 'destroyCategory']);
                Route::delete('/categories/{id}', [InventoryController::class, 'destroyCategory']);

                // Partners Mutations
                Route::post('/partners', [InventoryController::class, 'storePartner']);
                Route::put('/partners/{id}', [InventoryController::class, 'updatePartner']);
                Route::delete('/partners/{id}', [InventoryController::class, 'destroyPartner']);

                // Regions & Assignments Mutations
                Route::post('/regions', [RegionController::class, 'store']);
                Route::put('/regions/{id}', [RegionController::class, 'update']);
                Route::delete('/regions/{id}', [RegionController::class, 'destroy']);
                Route::post('/regions/{id}/assignments', [RegionController::class, 'storeRegionAssignment']);
                Route::post('/assignments', [RegionController::class, 'storeAssignment']);
                Route::post('/assignments/check-reminders', [RegionController::class, 'checkReminders']);
                Route::delete('/assignments/{id}', [RegionController::class, 'destroyAssignment']);

                // Sliders Mutations
                Route::post('/sliders', [SliderController::class, 'store']);
                Route::put('/sliders/{id}', [SliderController::class, 'update']);
                Route::delete('/sliders/{id}', [SliderController::class, 'destroy']);

                // Internal Settings
                Route::get('/settings', [SettingController::class, 'index']);
                Route::put('/settings', [SettingController::class, 'update']);
                Route::post('/settings/test-wa', [SettingController::class, 'testWhatsApp']);

                // Audit Logs
                Route::get('/audit-logs', [AuditLogController::class, 'index']);
            });

            // =========================================================
            // SUPER ADMIN EXCLUSIVE (Role: super_admin ONLY)
            // =========================================================
            Route::middleware('role:super_admin')->group(function () {
                // Admins Management
                Route::get('/admins', function (\Illuminate\Http\Request $request) {
                    $admins = \App\Models\User::whereIn('role', ['super_admin', 'admin', 'bendahara'])
                        ->orderBy('role', 'asc')
                        ->get();
                    return response()->json($admins);
                });

                Route::get('/admins/candidates', function (\Illuminate\Http\Request $request) {
                    $candidates = \App\Models\User::whereNotNull('email')
                        ->where('email', '!=', '')
                        ->where('role', '!=', 'super_admin')
                        ->orderBy('name', 'asc')
                        ->get();
                    return response()->json($candidates);
                });

                Route::post('/admins', function (\Illuminate\Http\Request $request) {
                    if ($request->filled('existing_user_id')) {
                        $request->validate([
                            'existing_user_id' => 'required|exists:users,id',
                            'role' => 'required|in:super_admin,admin,bendahara',
                        ]);
                        $user = \App\Models\User::findOrFail($request->existing_user_id);
                        $updateData = [
                            'role' => $request->role,
                            'is_active' => true,
                        ];
                        if ($request->filled('name')) {
                            $updateData['name'] = $request->name;
                        }
                        if ($request->filled('phone_wa')) {
                            $updateData['phone_wa'] = $request->phone_wa;
                        }
                        if ($request->filled('password')) {
                            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
                        }
                        $user->update($updateData);

                        $roleLabel = $request->role === 'super_admin' ? 'Super Admin' : ($request->role === 'bendahara' ? 'Bendahara' : 'Admin Operasional');
                        return response()->json([
                            'message' => "Akun {$user->name} berhasil diangkat menjadi {$roleLabel}.",
                            'admin' => $user,
                        ], 200);
                    }

                    $request->validate([
                        'name' => 'required|string|max:255',
                        'email' => 'required|email|unique:users,email',
                        'role' => 'required|in:super_admin,admin,bendahara',
                        'password' => 'required|string|min:8',
                        'phone_wa' => 'nullable|string',
                    ]);
                    $count = \App\Models\User::whereIn('role', ['super_admin', 'admin', 'bendahara'])->count() + 1;
                    $user = \App\Models\User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => $request->role,
                        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                        'phone_wa' => $request->phone_wa,
                        'member_no' => sprintf('ADM-%03d', $count),
                        'is_active' => true,
                    ]);
                    return response()->json(['message' => 'Akun pengelola admin berhasil ditambahkan.', 'admin' => $user], 201);
                });

                Route::delete('/admins/{id}', function (\Illuminate\Http\Request $request, $id) {
                    $user = \App\Models\User::findOrFail($id);
                    if ($user->role === 'super_admin') {
                        $superAdminCount = \App\Models\User::where('role', 'super_admin')->where('is_active', true)->count();
                        if ($superAdminCount <= 1) {
                            return response()->json(['message' => 'Tidak dapat menghapus Super Admin terakhir.'], 422);
                        }
                    }
                    $user->delete();
                    return response()->json(['message' => 'Pengelola admin berhasil dihapus.']);
                });

                // Danger Zone
                Route::post('/danger-zone/preview', [DangerZoneController::class, 'preview']);
                Route::post('/danger-zone/execute', [DangerZoneController::class, 'execute']);
            });
        });
    });
});
