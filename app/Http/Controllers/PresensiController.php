<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Presensi;
use App\Models\PresensiLog;
use App\Models\Rombel;
use App\Models\School;
use App\Models\Siswa;
use App\Services\WahaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PresensiController extends Controller
{
    protected WahaService $waha;

    public function __construct(WahaService $waha)
    {
        $this->waha = $waha;
    }

    /**
     * Mode A: Homeroom Teacher Morning Presensi Grid (Input Presensi Harian)
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $rombels = Rombel::with('waliKelas')->where('school_id', $schoolId)->get();
        $selectedRombelId = $request->get('rombel_id', $rombels->first()?->id);
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $siswas = collect();
        $presensis = collect();
        $selectedRombel = null;

        if ($selectedRombelId) {
            $selectedRombel = Rombel::with('waliKelas')->where('school_id', $schoolId)->find($selectedRombelId);

            $siswas = Siswa::where('school_id', $schoolId)
                ->where('rombel_id', $selectedRombelId)
                ->where('status', 'Aktif')
                ->orderBy('nama_lengkap', 'asc')
                ->get();

            $presensis = Presensi::where('school_id', $schoolId)
                ->where('rombel_id', $selectedRombelId)
                ->where('tanggal', $tanggal)
                ->get()
                ->keyBy('siswa_id');
        }

        return view('presensi.index', compact('rombels', 'selectedRombelId', 'selectedRombel', 'tanggal', 'siswas', 'presensis'));
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
            'catatan' => 'nullable|array',
        ]);

        $school = Auth::user()->school;
        $user = Auth::user();
        $rombelId = $request->rombel_id;
        $tanggal = $request->tanggal;

        $siswaIds = array_keys($request->presensi);
        $existingPresensis = Presensi::where('school_id', $school->id)
            ->whereIn('siswa_id', $siswaIds)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        $siswas = Siswa::whereIn('id', $siswaIds)->get()->keyBy('id');

        DB::transaction(function () use ($request, $school, $user, $rombelId, $tanggal, $existingPresensis, $siswas) {
            foreach ($request->presensi as $siswaId => $status) {
                $existing = $existingPresensis->get($siswaId);
                $statusLama = $existing ? $existing->status : null;
                $catatan = $request->catatan[$siswaId] ?? null;

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
                        'catatan' => $catatan,
                    ]
                );

                // Log change if status differs or newly created
                if ($statusLama !== $status) {
                    PresensiLog::create([
                        'presensi_id' => $presensi->id,
                        'user_id' => $user->id,
                        'action' => 'Guru Manual Entry',
                        'notes' => $statusLama ? "Status diubah: {$statusLama} -> {$status}" : "Presensi dicatat: {$status}",
                        'ip_address' => $request->ip(),
                    ]);
                }

                // Dispatch WhatsApp alert to parent if student is Sakit, Izin, Alpa, or Terlambat
                if (in_array($status, ['Sakit', 'Izin', 'Alpa', 'Terlambat']) && $statusLama !== $status) {
                    $siswa = $siswas->get($siswaId);
                    if ($siswa && $siswa->no_hp_ortu) {
                        $this->waha->sendAbsenceAlert(
                            $siswa->no_hp_ortu,
                            $siswa->nama_lengkap,
                            $tanggal,
                            $status,
                            $school->fonnte_token
                        );
                    }
                }
            }
        });

        return redirect()->route('presensi.index', [
            'rombel_id' => $rombelId,
            'tanggal' => $tanggal,
        ])->with('success', 'Presensi kelas harian berhasil disimpan!');
    }

    /**
     * Mode C: Laporan & Rekapitulasi Presensi Bulanan (Matriks Tanggal 1..31)
     */
    public function rekap(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $rombels = Rombel::with('waliKelas')->where('school_id', $schoolId)->get();

        $selectedRombelId = $request->get('rombel_id', $rombels->first()?->id);
        $tahun = (int) $request->get('tahun', now()->year);
        $bulan = (int) $request->get('bulan', now()->month);

        $selectedRombel = null;
        $recap = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        if ($selectedRombelId) {
            $selectedRombel = Rombel::with('waliKelas')->where('school_id', $schoolId)->find($selectedRombelId);
            $recap = $this->getMonthlyRecapData($schoolId, (int) $selectedRombelId, $tahun, $bulan);
        }

        return view('presensi.rekap', compact(
            'rombels',
            'selectedRombelId',
            'selectedRombel',
            'tahun',
            'bulan',
            'daysInMonth',
            'recap'
        ));
    }

    /**
     * Export Rekapitulasi Presensi Bulanan to PDF (Landscape A4)
     */
    public function exportPdf(Request $request)
    {
        $school = Auth::user()->school;
        $schoolId = $school->id;

        $rombelId = $request->get('rombel_id');
        $tahun = (int) $request->get('tahun', now()->year);
        $bulan = (int) $request->get('bulan', now()->month);

        if (!$rombelId) {
            return redirect()->back()->with('error', 'Silakan pilih rombel terlebih dahulu.');
        }

        $rombel = Rombel::with('waliKelas')->where('school_id', $schoolId)->findOrFail($rombelId);
        $recap = $this->getMonthlyRecapData($schoolId, (int) $rombelId, $tahun, $bulan);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $periodeLabel = ($namaBulan[$bulan] ?? '') . ' ' . $tahun;
        $printedAt = Carbon::now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('presensi.rekap_pdf', [
            'school' => $school,
            'rombel' => $rombel,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'periodeLabel' => $periodeLabel,
            'daysInMonth' => $daysInMonth,
            'recap' => $recap,
            'printedAt' => $printedAt,
        ])->setPaper('a4', 'landscape');

        $filename = 'Rekap_Presensi_' . str_replace(' ', '_', $rombel->nama_rombel) . "_{$tahun}_{$bulan}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Export Rekapitulasi Presensi Bulanan to CSV Spreadsheet
     */
    public function exportCsv(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $rombelId = $request->get('rombel_id');
        $tahun = (int) $request->get('tahun', now()->year);
        $bulan = (int) $request->get('bulan', now()->month);

        if (!$rombelId) {
            return redirect()->back()->with('error', 'Silakan pilih rombel terlebih dahulu.');
        }

        $rombel = Rombel::where('school_id', $schoolId)->findOrFail($rombelId);
        $recap = $this->getMonthlyRecapData($schoolId, (int) $rombelId, $tahun, $bulan);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $filename = "Rekap_Presensi_{$rombel->nama_rombel}_{$tahun}_{$bulan}.csv";

        $response = new StreamedResponse(function () use ($recap, $daysInMonth, $tahun, $bulan) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            // Header columns
            $headers = ['No', 'Nama Siswa', 'NISN', 'Jenis Kelamin'];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $headers[] = (string) $d;
            }
            $headers[] = 'Total Hadir';
            $headers[] = 'Total Sakit';
            $headers[] = 'Total Izin';
            $headers[] = 'Total Alpa';
            $headers[] = 'Total Terlambat';

            fputcsv($handle, $headers);

            foreach ($recap as $index => $row) {
                $line = [
                    $index + 1,
                    $row['nama_lengkap'],
                    $row['nisn'],
                    $row['jenis_kelamin'],
                ];

                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $dateStr = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                    $status = $row['details'][$dateStr] ?? '-';
                    $line[] = match($status) {
                        'Hadir' => 'H',
                        'Sakit' => 'S',
                        'Izin' => 'I',
                        'Alpa' => 'A',
                        'Terlambat' => 'T',
                        default => '-'
                    };
                }

                $line[] = $row['hadir'];
                $line[] = $row['sakit'];
                $line[] = $row['izin'];
                $line[] = $row['alpa'];
                $line[] = $row['terlambat'];

                fputcsv($handle, $line);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
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

    /**
     * Helper function: Calculate monthly attendance matrix and totals per student
     */
    protected function getMonthlyRecapData(int $schoolId, int $rombelId, int $year, int $month): array
    {
        $siswas = Siswa::where('school_id', $schoolId)
            ->where('rombel_id', $rombelId)
            ->where('status', 'Aktif')
            ->orderBy('nama_lengkap', 'asc')
            ->get(['id', 'nisn', 'nama_lengkap', 'jenis_kelamin']);

        $presensisBySiswa = Presensi::where('school_id', $schoolId)
            ->where('rombel_id', $rombelId)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get(['siswa_id', 'tanggal', 'status'])
            ->groupBy('siswa_id');

        return $siswas->map(function ($s) use ($presensisBySiswa) {
            $studentPresensis = $presensisBySiswa->get($s->id, collect());

            $sHadir = $studentPresensis->where('status', 'Hadir')->count();
            $sSakit = $studentPresensis->where('status', 'Sakit')->count();
            $sIzin = $studentPresensis->where('status', 'Izin')->count();
            $sAlpa = $studentPresensis->where('status', 'Alpa')->count();
            $sTerlambat = $studentPresensis->where('status', 'Terlambat')->count();

            $sDetails = $studentPresensis->mapWithKeys(function ($item) {
                return [$item->tanggal->format('Y-m-d') => $item->status];
            })->all();

            return [
                'id' => $s->id,
                'nisn' => $s->nisn ?: '-',
                'nama_lengkap' => $s->nama_lengkap,
                'jenis_kelamin' => $s->jenis_kelamin ?: '-',
                'hadir' => $sHadir,
                'sakit' => $sSakit,
                'izin' => $sIzin,
                'alpa' => $sAlpa,
                'terlambat' => $sTerlambat,
                'details' => $sDetails,
            ];
        })->toArray();
    }
}
