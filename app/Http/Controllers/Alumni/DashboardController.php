<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard alumni dengan data lengkap
     *
     * @return View
     */
    public function index(): View
    {
        $alumni = Alumni::with(['user', 'angkatan', 'pendidikan', 'pekerjaan', 'fotos'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('alumni.dashboard', compact('alumni'));
    }
}
