<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Hidden activity log viewer — accessible only via secret URL.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        // Filter by type
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        // Filter by user
        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }

        // Filter by IP
        if ($ip = $request->get('ip')) {
            $query->where('ip_address', 'like', "%{$ip}%");
        }

        // Filter by date range
        if ($from = $request->get('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Search description
        if ($search = $request->get('search')) {
            $query->where('description', 'like', "%{$search}%");
        }

        $logs = $query->paginate(50)->withQueryString();

        // Stats
        $stats = [
            'total'         => ActivityLog::count(),
            'logins_today'  => ActivityLog::where('type', 'login')->whereDate('created_at', today())->count(),
            'failed_today'  => ActivityLog::where('type', 'login_failed')->whereDate('created_at', today())->count(),
            'unique_ips'    => ActivityLog::distinct('ip_address')->count('ip_address'),
        ];

        // Available types for filter dropdown
        $types = ActivityLog::distinct()->pluck('type')->sort()->values();

        return view('admin.activity-logs.index', compact('logs', 'stats', 'types'));
    }

    /**
     * Clear all activity logs.
     */
    public function clear(Request $request)
    {
        $days = (int) $request->get('days', 0);

        if ($days > 0) {
            ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
            $msg = "Activity logs older than $days days cleared.";
        } else {
            ActivityLog::truncate();
            $msg = "All activity logs cleared.";
        }

        return redirect()->back()->with('success', $msg);
    }
}
