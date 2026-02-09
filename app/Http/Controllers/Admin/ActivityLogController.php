<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan activity logs dengan fitur filter dan search
     */
    public function index(Request $request)
    {
        $query = AdminLog::with('admin');

        // Filter berdasarkan action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Search berdasarkan deskripsi
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Mengambil data terbaru dengan pagination 30 data per halaman
        $logs = $query->latest()->paginate(30)->withQueryString();

        // Ambil daftar unik action untuk menu dropdown filter
        $actions = AdminLog::distinct()->pluck('action');

        return view('admin.logs.index', compact('logs', 'actions'));
    }

    /**
     * Hapus satu baris log tertentu
     */
    public function destroy(AdminLog $log)
    {
        $log->delete();

        return back()->with('success', 'Log berhasil dihapus!');
    }

    /**
     * Kosongkan seluruh tabel activity logs
     */
    public function clearAll()
    {
        AdminLog::truncate();

        return back()->with('success', 'Semua activity logs berhasil dihapus!');
    }
}
