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

        $presensiStats = Presensi::where('school_id', $schoolId)
            ->where('tanggal', $today)
            ->selectRaw('count(*) as total, count(case when status = "Hadir" then 1 end) as hadir')
            ->first();

        $stats = [
            'total_siswa' => Siswa::where('school_id', $schoolId)->where('status', 'Aktif')->count(),
            'total_guru' => Guru::where('school_id', $schoolId)->count(),
            'total_rombel' => Rombel::where('school_id', $schoolId)->count(),
            'presensi_today' => $presensiStats->total ?? 0,
            'presensi_hadir' => $presensiStats->hadir ?? 0,
            'pending_spp' => TagihanSpp::where('school_id', $schoolId)->where('status', 'Menunggu Verifikasi')->count(),
            'pending_expense' => Expense::where('school_id', $schoolId)->where('status', 'Diajukan')->count(),
            'unpaid_spp' => TagihanSpp::where('school_id', $schoolId)->where('status', 'Belum Lunas')->count(),
        ];

        // Fetch recent activities or items with eager loading
        $recentExpenses = Expense::where('school_id', $schoolId)->with(['user:id,name', 'category:id,nama_kategori'])->latest()->take(5)->get();
        $recentSppPending = TagihanSpp::where('school_id', $schoolId)->where('status', 'Menunggu Verifikasi')->with(['siswa:id,nama_lengkap,nisn', 'pembayarans'])->latest()->take(5)->get();

        return view('dashboard', compact('user', 'stats', 'recentExpenses', 'recentSppPending'));
    }
}
