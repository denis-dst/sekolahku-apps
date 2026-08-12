<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\PresensiLog;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    protected FonnteService $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    /**
     * Mode A: Homeroom Teacher Morning Presensi Grid
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $rombels = Rombel::where('school_id', $schoolId)->get();
        $selectedRombelId = $request->get('rombel_id', $rombels->first()?->id);
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $siswas = collect();
        $presensis = collect();

        if ($selectedRombelId) {
            $siswas = Siswa::where('school_id', $schoolId)
                ->where('rombel_id', $selectedRombelId)
                ->where('status', 'Aktif')
                ->get();

            $presensis = Presensi::where('school_id', $schoolId)
                ->where('rombel_id', $selectedRombelId)
                ->where('tanggal', $tanggal)
                ->get()
                ->keyBy('siswa_id');
        }

        return view('presensi.index', compact('rombels', 'selectedRombelId', 'tanggal', 'siswas', 'presensis'));
    }

    /**
     * Store Morning Whole-Class Attendance (Guru Kelas)
     */
    public function storeGuru(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'tanggal' => 'required|date',
            'presensi' => 'required|array', // [siswa_id => status]
        ]);

        $school = Auth::user()->school;
        $user = Auth::user();
        $rombelId = $request->rombel_id;
        $tanggal = $request->tanggal;

        foreach ($request->presensi as $siswaId => $status) {
            $existing = Presensi::where('school_id', $school->id)
                ->where('siswa_id', $siswaId)
                ->where('tanggal', $tanggal)
                ->first();

            $presensi = Presensi::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                ],
                [
                    'rombel_id' => $rombelId,
                    'status' => $status,
                    'jam_masuk' => $existing?->jam_masuk ?: now()->format('H:i:s'),
                    'entry_type' => 'guru_manual',
                    'catatan' => $request->catatan[$siswaId] ?? null,
                ]
            );

            PresensiLog::create([
                'presensi_id' => $presensi->id,
                'user_id' => $user->id,
                'action' => 'Guru Manual Entry',
                'notes' => "Status: {$status}",
                'ip_address' => $request->ip(),
            ]);

            // Dispatch Fonnte WhatsApp alert to parent if student is Sakit, Izin, Alpa, or Terlambat
            if (in_array($status, ['Sakit', 'Izin', 'Alpa', 'Terlambat'])) {
                $siswa = Siswa::find($siswaId);
                if ($siswa && $siswa->no_hp_ortu) {
                    $this->fonnte->sendAbsenceAlert(
                        $siswa->no_hp_ortu,
                        $siswa->nama_lengkap,
                        $tanggal,
                        $status,
                        $school->fonnte_token
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Presensi kelas berhasil disimpan & pemberitahuan WhatsApp Fonnte telah dikirimkan!');
    }

    /**
     * Mode B: Student Self-Attendance Portal (Absen Mandiri Siswa)
     */
    public function showMandiri()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $today = now()->format('Y-m-d');

        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terhubung dengan data siswa.');
        }

        $presensiToday = Presensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->first();

        return view('presensi.mandiri', compact('siswa', 'today', 'presensiToday'));
    }

    /**
     * Store Student Self-Attendance
     */
    public function storeMandiri(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i:s');

        if (!$siswa || !$siswa->rombel_id) {
            return redirect()->back()->with('error', 'Data siswa atau rombel tidak ditemukan.');
        }

        $existing = Presensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->first();

        if ($existing) {
            return redirect()->back()->with('warning', 'Anda sudah melakukan presensi mandiri hari ini pada jam ' . $existing->jam_masuk);
        }

        // Check if student is late (e.g. after 07:30 WIB)
        $status = now()->format('H:i') > '07:30' ? 'Terlambat' : 'Hadir';

        $presensi = Presensi::create([
            'school_id' => $siswa->school_id,
            'rombel_id' => $siswa->rombel_id,
            'siswa_id' => $siswa->id,
            'tanggal' => $today,
            'status' => $status,
            'jam_masuk' => $currentTime,
            'entry_type' => 'siswa_mandiri',
            'catatan' => 'Absen Mandiri Siswa via Portal',
        ]);

        PresensiLog::create([
            'presensi_id' => $presensi->id,
            'user_id' => $user->id,
            'action' => 'Siswa Self Check-in',
            'notes' => "Absen mandiri jam {$currentTime} ({$status})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', "Absen mandiri berhasil dicatat! Jam Masuk: {$currentTime} ({$status})");
    }
}
