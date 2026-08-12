<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Rombel;
use App\Models\Presensi;
use App\Models\TagihanSpp;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schoolId = $user->school_id;
        $today = now()->format('Y-m-d');

        $stats = [
            'total_siswa' => Siswa::where('school_id', $schoolId)->where('status', 'Aktif')->count(),
            'total_guru' => Guru::where('school_id', $schoolId)->count(),
            'total_rombel' => Rombel::where('school_id', $schoolId)->count(),
            'presensi_today' => Presensi::where('school_id', $schoolId)->where('tanggal', $today)->count(),
            'presensi_hadir' => Presensi::where('school_id', $schoolId)->where('tanggal', $today)->where('status', 'Hadir')->count(),
            'pending_spp' => TagihanSpp::where('school_id', $schoolId)->where('status', 'Menunggu Verifikasi')->count(),
            'pending_expense' => Expense::where('school_id', $schoolId)->where('status', 'Diajukan')->count(),
            'unpaid_spp' => TagihanSpp::where('school_id', $schoolId)->where('status', 'Belum Lunas')->count(),
        ];

        // Fetch recent activities or items
        $recentExpenses = Expense::where('school_id', $schoolId)->with(['user', 'category'])->latest()->take(5)->get();
        $recentSppPending = TagihanSpp::where('school_id', $schoolId)->where('status', 'Menunggu Verifikasi')->with(['siswa', 'pembayarans'])->latest()->take(5)->get();

        return view('dashboard', compact('user', 'stats', 'recentExpenses', 'recentSppPending'));
    }
}
