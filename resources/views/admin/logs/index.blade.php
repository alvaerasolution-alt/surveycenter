@extends('layouts.admin')

@section('title', 'System Logs')
@section('page-title', 'System Logs')

@section('content')
<style>
    .log-container {
        font-family: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', 'Consolas', monospace;
        font-size: 12px;
        line-height: 1.6;
        background: #0d1117;
        color: #c9d1d9;
        border-radius: 12px;
        overflow: hidden;
    }
    .log-line {
        padding: 2px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        white-space: pre-wrap;
        word-break: break-all;
        transition: background 0.15s;
    }
    .log-line:hover {
        background: rgba(255,255,255,0.04);
    }
    /* Log levels */
    .log-line.error, .log-line.critical, .log-line.alert, .log-line.emergency {
        color: #f85149;
        background: rgba(248,81,73,0.06);
    }
    .log-line.warning {
        color: #d29922;
        background: rgba(210,153,34,0.06);
    }
    .log-line.info {
        color: #58a6ff;
    }
    .log-line.debug {
        color: #8b949e;
    }
    .log-toolbar {
        background: #161b22;
        border-bottom: 1px solid #30363d;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .log-toolbar select, .log-toolbar input {
        background: #0d1117;
        border: 1px solid #30363d;
        color: #c9d1d9;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
    }
    .log-toolbar select:focus, .log-toolbar input:focus {
        outline: none;
        border-color: #58a6ff;
        box-shadow: 0 0 0 3px rgba(88,166,255,0.15);
    }
    .log-toolbar button, .log-toolbar a.btn-log {
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #30363d;
        transition: all 0.15s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-search {
        background: #238636;
        color: #fff;
        border-color: #238636 !important;
    }
    .btn-search:hover { background: #2ea043; }
    .btn-download {
        background: #1f6feb;
        color: #fff;
        border-color: #1f6feb !important;
    }
    .btn-download:hover { background: #388bfd; }
    .btn-clear {
        background: #da3633;
        color: #fff;
        border-color: #da3633 !important;
    }
    .btn-clear:hover { background: #f85149; }
    .btn-refresh {
        background: #30363d;
        color: #c9d1d9;
    }
    .btn-refresh:hover { background: #484f58; }
    .log-body {
        max-height: 70vh;
        overflow-y: auto;
        padding: 8px 0;
    }
    .log-body::-webkit-scrollbar { width: 8px; }
    .log-body::-webkit-scrollbar-track { background: #0d1117; }
    .log-body::-webkit-scrollbar-thumb { background: #30363d; border-radius: 4px; }
    .log-body::-webkit-scrollbar-thumb:hover { background: #484f58; }
    .log-stats {
        background: #161b22;
        border-top: 1px solid #30363d;
        padding: 8px 16px;
        font-size: 12px;
        color: #8b949e;
        display: flex;
        justify-content: space-between;
    }
    .empty-log {
        text-align: center;
        padding: 60px 20px;
        color: #484f58;
    }
    .empty-log svg { margin-bottom: 12px; }
</style>

<div class="space-y-4">

    {{-- Flash message --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Log Viewer --}}
    <div class="log-container shadow-xl">

        {{-- Toolbar --}}
        <form method="GET" action="{{ route('admin.logs.index') }}" class="log-toolbar">
            <select name="file" onchange="this.form.submit()">
                @foreach($logFiles as $logFile)
                    <option value="{{ $logFile->getFilename() }}" {{ $selected === $logFile->getFilename() ? 'selected' : '' }}>
                        {{ $logFile->getFilename() }} ({{ number_format($logFile->getSize() / 1024, 1) }} KB)
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" value="{{ $search }}" placeholder="Search logs..." style="min-width: 200px;">

            <select name="lines">
                @foreach([50, 100, 200, 500, 1000] as $n)
                    <option value="{{ $n }}" {{ (int)$tail === $n ? 'selected' : '' }}>Last {{ $n }} lines</option>
                @endforeach
            </select>

            <button type="submit" class="btn-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                Filter
            </button>

            <a href="{{ route('admin.logs.index', ['file' => $selected]) }}" class="btn-log btn-refresh">
                ↻ Refresh
            </a>

            <a href="{{ route('admin.logs.download', ['file' => $selected]) }}" class="btn-log btn-download">
                ↓ Download
            </a>

            <button type="button" class="btn-clear" onclick="if(confirm('Clear this log file?')) document.getElementById('clearForm').submit();">
                ✕ Clear
            </button>
        </form>

        {{-- Log content --}}
        <div class="log-body" id="logBody">
            @if(count($lines) > 0)
                @foreach($lines as $line)
                    @php
                        $levelClass = '';
                        if (preg_match('/\.(ERROR|CRITICAL|ALERT|EMERGENCY)/i', $line)) $levelClass = 'error';
                        elseif (preg_match('/\.WARNING/i', $line)) $levelClass = 'warning';
                        elseif (preg_match('/\.INFO/i', $line)) $levelClass = 'info';
                        elseif (preg_match('/\.DEBUG/i', $line)) $levelClass = 'debug';
                    @endphp
                    <div class="log-line {{ $levelClass }}">{{ $line }}</div>
                @endforeach
            @else
                <div class="empty-log">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="display:inline-block"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p>No log entries found{{ $search ? " matching \"$search\"" : '' }}.</p>
                </div>
            @endif
        </div>

        {{-- Footer stats --}}
        <div class="log-stats">
            <span>Showing {{ count($lines) }} lines from <strong>{{ $selected }}</strong></span>
            <span>{{ $search ? "Filter: \"$search\"" : 'No filter applied' }}</span>
        </div>
    </div>
</div>

{{-- Clear form --}}
<form id="clearForm" method="POST" action="{{ route('admin.logs.clear') }}" style="display:none;">
    @csrf
    <input type="hidden" name="file" value="{{ $selected }}">
</form>

{{-- Auto-scroll to bottom --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logBody = document.getElementById('logBody');
    if (logBody) logBody.scrollTop = logBody.scrollHeight;
});
</script>
@endsection
