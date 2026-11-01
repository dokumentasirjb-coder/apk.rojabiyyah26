<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use App\Models\FieldReport;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_members' => User::where('role', 'anggota')->count(),
            'total_items' => Item::count(),
            'active_loans' => Loan::whereIn('status', ['disetujui', 'diserahkan'])->count(),
            'pending_loans' => Loan::where('status', 'diajukan')->count(),
            'total_regions' => Region::count(),
            'pending_reports' => FieldReport::where('followup_status', 'open')->count(),
        ];

        // Cash balance for authorized roles
        if (in_array($user->role, ['super_admin', 'bendahara'])) {
            $in = CashTransaction::active()->where('type', 'in')->sum('amount');
            $out = CashTransaction::active()->where('type', 'out')->sum('amount');
            $stats['cash_balance'] = (float)($in - $out);
        }

        // Recent loans
        $loanQuery = Loan::with(['borrower', 'loanItems.item'])->orderBy('requested_at', 'desc')->limit(5);
        if ($user->role === 'anggota') {
            $loanQuery->where('borrower_id', $user->id);
        }
        $stats['recent_loans'] = $loanQuery->get();

        // Recent reports
        $stats['recent_reports'] = FieldReport::with(['region', 'reporter'])->orderBy('created_at', 'desc')->limit(5)->get();

        return response()->json($stats);
    }
}

