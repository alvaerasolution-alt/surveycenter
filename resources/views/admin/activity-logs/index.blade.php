@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')
<style>
    .stat-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 12px;
        padding: 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: -20px; right: -20px;
        width: 80px; height: 80px;
        border-radius: 50%;
        opacity: 0.08;
    }
    .stat-card.blue::after { background: #3b82f6; }
    .stat-card.green::after { background: #22c55e; }
    .stat-card.red::after { background: #ef4444; }
    .stat-card.purple::after { background: #8b5cf6; }
    .stat-value { font-size: 28px; font-weight: 800; line-height: 1; }
    .stat-label { font-size: 12px; color: #94a3b8; margin-top: 4px; }

    .activity-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .activity-table th {
        background: #f8fafc;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        padding: 10px 14px;
        border-bottom: 2px solid #e2e8f0;
        text-align: left;
    }
    .activity-table td {
        padding: 10px 14px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }
    .activity-table tr:hover td { background: #f8fafc; }

    .badge-type {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .badge-login { background: #dcfce7; color: #166534; }
    .badge-logout { background: #e0e7ff; color: #3730a3; }
    .badge-login_failed { background: #fee2e2; color: #991b1b; }
    .badge-page_view { background: #f0f9ff; color: #0c4a6e; }
    .badge-action { background: #fef9c3; color: #854d0e; }
    .badge-default { background: #f1f5f9; color: #475569; }

    .filter-bar {
        display: flex; flex-wrap: wrap; gap: 8px; align-items: center;
        background: #fff; padding: 12px 16px; border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .filter-bar input, .filter-bar select {
        padding: 6px 10px; border: 1px solid #e2e8f0; border-radius: 6px;
        font-size: 13px; color: #334155; background: #f8fafc;
    }
    .filter-bar input:focus, .filter-bar select:focus {
        outline: none; border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.15);
    }
    .filter-btn {
        padding: 6px 14px; border-radius: 6px; font-size: 13px;
        font-weight: 600; cursor: pointer; border: none; transition: all 0.15s;
    }
    .ip-mono { font-family: 'JetBrains Mono', monospace; font-size: 12px; color: #6366f1; }
    .ua-text { font-size: 11px; color: #94a3b8; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .url-text { font-size: 11px; color: #64748b; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>

<div class="space-y-5">

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="stat-card blue">
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
            <div class="stat-label">Total Activities</div>
        </div>
        <div class="stat-card green">
            <div class="stat-value">{{ $stats['logins_today'] }}</div>
            <div class="stat-label">Logins Today</div>
        </div>
        <div class="stat-card red">
            <div class="stat-value">{{ $stats['failed_today'] }}</div>
            <div class="stat-label">Failed Logins Today</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-value">{{ $stats['unique_ips'] }}</div>
            <div class="stat-label">Unique IPs</div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="filter-bar">
        <select name="type">
            <option value="">All Types</option>
            @foreach($types as $t)
                <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $t)) }}</option>
            @endforeach
        </select>

        <input type="text" name="ip" value="{{ request('ip') }}" placeholder="Filter by IP..." style="width:140px;">

        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description..." style="width:180px;">

        <input type="date" name="from" value="{{ request('from') }}" title="From date">
        <input type="date" name="to" value="{{ request('to') }}" title="To date">

        <button type="submit" class="filter-btn" style="background:#3b82f6;color:#fff;">
            Filter
        </button>
        <a href="{{ route('admin.activity-logs.index') }}" class="filter-btn" style="background:#f1f5f9;color:#475569;">
            Reset
        </a>

        <div class="ml-auto flex gap-2">
            <button type="button" class="filter-btn" style="background:#fee2e2;color:#991b1b;"
                    onclick="if(confirm('Clear logs older than 30 days?')) document.getElementById('clearForm30').submit();">
                Clear 30d+
            </button>
            <button type="button" class="filter-btn" style="background:#ef4444;color:#fff;"
                    onclick="if(confirm('Clear ALL activity logs? This cannot be undone.')) document.getElementById('clearFormAll').submit();">
                Clear All
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Type</th>
                        <th>User</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="white-space:nowrap;">
                            <div class="font-semibold text-xs">{{ $log->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($log->type) {
                                    'login' => 'badge-login',
                                    'logout' => 'badge-logout',
                                    'login_failed' => 'badge-login_failed',
                                    'page_view' => 'badge-page_view',
                                    'action' => 'badge-action',
                                    default => 'badge-default',
                                };
                            @endphp
                            <span class="badge-type {{ $badgeClass }}">{{ str_replace('_', ' ', $log->type) }}</span>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="font-medium text-sm">{{ $log->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $log->user->email }}</div>
                            @else
                                <span class="text-gray-400 text-xs">Guest</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($log->description, 60) }}</td>
                        <td><span class="ip-mono">{{ $log->ip_address ?? '-' }}</span></td>
                        <td>
                            <div class="ua-text" title="{{ $log->user_agent }}">
                                {{ Str::limit($log->user_agent, 40) }}
                            </div>
                        </td>
                        <td>
                            <div class="url-text" title="{{ $log->url }}">
                                {{ $log->url ? Str::limit(parse_url($log->url, PHP_URL_PATH), 30) : '-' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            No activity logs recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Clear forms --}}
<form id="clearForm30" method="POST" action="{{ route('admin.activity-logs.clear') }}" style="display:none;">
    @csrf
    <input type="hidden" name="days" value="30">
</form>
<form id="clearFormAll" method="POST" action="{{ route('admin.activity-logs.clear') }}" style="display:none;">
    @csrf
    <input type="hidden" name="days" value="0">
</form>
@endsection
