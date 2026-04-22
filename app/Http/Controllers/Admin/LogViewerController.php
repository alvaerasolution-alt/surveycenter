<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    /**
     * Hidden log viewer — accessible only via secret URL.
     * Not linked from any admin dashboard or navigation.
     */
    public function index(Request $request)
    {
        $logPath = storage_path('logs');
        $logFiles = collect(File::files($logPath))
            ->filter(fn($f) => $f->getExtension() === 'log')
            ->sortByDesc(fn($f) => $f->getMTime())
            ->values();

        // Which file to view (default: latest)
        $selected = $request->get('file', $logFiles->first()?->getFilename());
        $filePath = $logPath . '/' . basename($selected); // basename prevents path traversal

        $content = '';
        $lines = [];
        if (File::exists($filePath)) {
            // Tail: read last N lines for performance
            $tail = (int) $request->get('lines', 200);
            $allLines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $lines = array_slice($allLines, -$tail);
        }

        // Optional search filter
        $search = $request->get('search', '');
        if ($search) {
            $lines = array_filter($lines, fn($line) => stripos($line, $search) !== false);
            $lines = array_values($lines);
        }

        return view('admin.logs.index', [
            'logFiles' => $logFiles,
            'selected' => $selected,
            'lines'    => $lines,
            'search'   => $search,
            'tail'     => $request->get('lines', 200),
        ]);
    }

    /**
     * Download a log file.
     */
    public function download(Request $request)
    {
        $file = basename($request->get('file', 'laravel.log'));
        $path = storage_path('logs/' . $file);

        if (!File::exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    /**
     * Clear a log file.
     */
    public function clear(Request $request)
    {
        $file = basename($request->get('file', 'laravel.log'));
        $path = storage_path('logs/' . $file);

        if (File::exists($path)) {
            File::put($path, '');
        }

        return redirect()->back()->with('success', "Log file '$file' cleared.");
    }
}
