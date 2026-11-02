<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AuditLogController;
use App\Http\Controllers\Api\V1\CashController;
use App\Http\Controllers\Api\V1\DangerZoneController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\DivisionController;
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
    // Public routes (Read-only for guests)
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/public/overview', [PublicEventController::class, 'overview']);
    Route::get('/public/landing', [PublicEventController::class, 'overview']);
    Route::get('/public/sliders', [SliderController::class, 'publicSliders']);
    Route::get('/public/reports', [FieldReportController::class, 'publicReports']);
    Route::get('/public/loans/verify/{code}', [LoanController::class, 'verifyPublic']);
    Route::get('/public/loans/{id}/pdf', [LoanController::class, 'generatePdf']);
    Route::get('/settings/public', [SettingController::class, 'index']);
    Route::get('/public/options', [MemberController::class, 'publicOptions']);
    Route::post('/public/register-member', [MemberController::class, 'publicRegister']);
    Route::post('/public/register-account', [MemberController::class, 'publicRegisterAccount']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Super Admin & Admin: Landing Page Management
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

        // Auth & Profile
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/password/change', [AuthController::class, 'changePassword']);

        // Dashboard Stats
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        // Members & Divisions
        Route::get('/members', [MemberController::class, 'index']);
        Route::post('/members', [MemberController::class, 'store']);
        Route::get('/members/export', [MemberController::class, 'export']);
        Route::get('/members/template', [MemberController::class, 'template']);
        Route::post('/members/import/preview', [MemberController::class, 'importPreview']);
        Route::post('/members/import', [MemberController::class, 'importExecute']);
        Route::post('/members/{id}/create-account', [MemberController::class, 'createAccount']);
        Route::post('/members/{id}/revoke-account', [MemberController::class, 'revokeAccount']);
        Route::get('/members/{id}', [MemberController::class, 'show']);
        Route::put('/members/{id}', [MemberController::class, 'update']);
        Route::delete('/members/{id}', [MemberController::class, 'destroy']);
        Route::get('/divisions', [DivisionController::class, 'index']);
        Route::post('/divisions', [DivisionController::class, 'store']);
        Route::get('/divisions/{id}', [DivisionController::class, 'show']);
        Route::put('/divisions/{id}', [DivisionController::class, 'update']);
        Route::delete('/divisions/{id}', [DivisionController::class, 'destroy']);

        // Inventory & Logistics
        Route::get('/items', [InventoryController::class, 'index']);
        Route::post('/items', [InventoryController::class, 'store']);
        Route::get('/items/export', [InventoryController::class, 'export']);
        Route::get('/items/template', [InventoryController::class, 'template']);
        Route::post('/items/import/preview', [InventoryController::class, 'importPreview']);
        Route::post('/items/import', [InventoryController::class, 'importExecute']);
        Route::get('/items/{id}', [InventoryController::class, 'show']);
        Route::put('/items/{id}', [InventoryController::class, 'update']);
        Route::delete('/items/{id}', [InventoryController::class, 'destroy']);
        Route::get('/inventory-categories', [InventoryController::class, 'categories']);
        Route::get('/categories', [InventoryController::class, 'categories']);
        Route::post('/inventory-categories', [InventoryController::class, 'storeCategory']);
        Route::delete('/inventory-categories/{id}', [InventoryController::class, 'destroyCategory']);
        Route::delete('/categories/{id}', [InventoryController::class, 'destroyCategory']);

        // Partners / Lembaga Mitra
        Route::get('/partners', [InventoryController::class, 'partners']);
        Route::post('/partners', [InventoryController::class, 'storePartner']);
        Route::put('/partners/{id}', [InventoryController::class, 'updatePartner']);
        Route::delete('/partners/{id}', [InventoryController::class, 'destroyPartner']);

        // Loans & Circulation
        Route::get('/loans', [LoanController::class, 'index']);
        Route::post('/loans', [LoanController::class, 'store']);
        Route::get('/loans/{id}', [LoanController::class, 'show']);
        Route::post('/loans/{id}/approve', [LoanController::class, 'approve']);
        Route::post('/loans/{id}/reject', [LoanController::class, 'reject']);
        Route::post('/loans/{id}/handover', [LoanController::class, 'handover']);
        Route::post('/loans/{id}/return', [LoanController::class, 'returnLoan']);
        Route::post('/loans/{id}/cancel', [LoanController::class, 'cancel']);
        Route::get('/loans/{id}/pdf', [LoanController::class, 'generatePdf']);

        // Regions / Posko & Assignments
        Route::get('/regions', [RegionController::class, 'index']);
        Route::post('/regions', [RegionController::class, 'store']);
        Route::put('/regions/{id}', [RegionController::class, 'update']);
        Route::delete('/regions/{id}', [RegionController::class, 'destroy']);
        Route::get('/regions/{id}/assignments', [RegionController::class, 'regionAssignments']);
        Route::post('/regions/{id}/assignments', [RegionController::class, 'storeRegionAssignment']);
        Route::get('/assignments', [RegionController::class, 'assignments']);
        Route::post('/assignments', [RegionController::class, 'storeAssignment']);
        Route::delete('/assignments/{id}', [RegionController::class, 'destroyAssignment']);

        // Field Reports
        Route::get('/reports', [FieldReportController::class, 'index']);
        Route::post('/reports', [FieldReportController::class, 'store']);
        Route::get('/reports/{id}', [FieldReportController::class, 'show']);
        Route::post('/reports/{id}/followups', [FieldReportController::class, 'addFollowup']);
        Route::patch('/reports/{id}/status', [FieldReportController::class, 'updateStatus']);

        // Cash Management (Khusus Super Admin & Bendahara)
        // Verifikasi password akses kas
        Route::post('/cash/verify-password', function (\Illuminate\Http\Request $request) {
            if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
            $request->validate(['password' => 'required|string']);
            $correct = \App\Models\Setting::get('cash_password', 'kas2026');
            if ($request->password === $correct) {
                return response()->json(['valid' => true, 'message' => 'Akses kas berhasil diverifikasi.']);
            }
            return response()->json(['valid' => false, 'message' => 'Kata sandi salah. Silakan coba lagi.'], 401);
        });

        Route::get('/cash', function (\Illuminate\Http\Request $request) {
            if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                return response()->json(['message' => 'Akses ditolak. Hanya Super Admin dan Bendahara.'], 403);
            }
            return app(\App\Http\Controllers\Api\V1\CashController::class)->index($request);
        });
        Route::post('/cash', [CashController::class, 'store']);
        Route::put('/cash/{id}', function (\Illuminate\Http\Request $request, $id) {
            if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
            return app(\App\Http\Controllers\Api\V1\CashController::class)->update($request, $id);
        });
        Route::delete('/cash/{id}', function (\Illuminate\Http\Request $request, $id) {
            if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
            return app(\App\Http\Controllers\Api\V1\CashController::class)->destroy($request, $id);
        });
        Route::post('/cash/{id}/void', function (\Illuminate\Http\Request $request, $id) {
            if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
            return app(\App\Http\Controllers\Api\V1\CashController::class)->voidTransaction($request, $id);
        });
        Route::get('/cash/summary', function (\Illuminate\Http\Request $request) {
            if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
            return app(\App\Http\Controllers\Api\V1\CashController::class)->summary($request);
        });
        Route::get('/cash/categories', function (\Illuminate\Http\Request $request) {
            if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
            return app(\App\Http\Controllers\Api\V1\CashController::class)->categories($request);
        });
        Route::post('/cash/categories', function (\Illuminate\Http\Request $request) {
            if (!in_array($request->user()->role, ['super_admin', 'bendahara'])) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
            return app(\App\Http\Controllers\Api\V1\CashController::class)->storeCategory($request);
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

        // Sliders
        Route::get('/sliders', [SliderController::class, 'index']);
        Route::post('/sliders', [SliderController::class, 'store']);
        Route::put('/sliders/{id}', [SliderController::class, 'update']);
        Route::delete('/sliders/{id}', [SliderController::class, 'destroy']);

        // Settings
        Route::get('/settings', [SettingController::class, 'index']);
        Route::put('/settings', [SettingController::class, 'update']);

        // Admins Management
        Route::get('/admins', function () {
            $admins = \App\Models\User::whereIn('role', ['super_admin', 'admin', 'bendahara'])
                ->orderBy('role', 'asc')
                ->get();
            return response()->json($admins);
        });

        Route::get('/admins/candidates', function () {
            $candidates = \App\Models\User::whereNotNull('email')
                ->where('email', '!=', '')
                ->where('role', '!=', 'super_admin')
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'email', 'phone_wa', 'member_no', 'role']);
            return response()->json($candidates);
        });

        Route::post('/admins', function (\Illuminate\Http\Request $request) {
            if ($request->filled('user_id')) {
                $request->validate([
                    'user_id' => 'required|exists:users,id',
                    'role' => 'required|in:super_admin,admin,bendahara',
                    'password' => 'nullable|string|min:6',
                ]);
                $user = \App\Models\User::findOrFail($request->user_id);
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
                'password' => 'required|string|min:6',
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
        Route::delete('/admins/{id}', function ($id) {
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

        // Audit Logs & Danger Zone
        Route::get('/audit-logs', [AuditLogController::class, 'index']);
        Route::post('/danger-zone/preview', [DangerZoneController::class, 'preview']);
        Route::post('/danger-zone/execute', [DangerZoneController::class, 'execute']);
    });
});
