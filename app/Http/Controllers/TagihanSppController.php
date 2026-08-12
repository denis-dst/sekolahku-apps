<?php

namespace App\Http\Controllers;

use App\Models\TagihanSpp;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagihanSppController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $user = Auth::user();

        $query = TagihanSpp::where('school_id', $schoolId)->with(['siswa', 'tahunAjaran', 'pembayarans']);

        if ($user->hasRole('Orang Tua')) {
            // Filter by parent's student
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        } elseif ($user->hasRole('Siswa')) {
            $siswa = $user->siswa;
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tagihans = $query->latest()->paginate(15);
        $school = $user->school;

        return view('spp.index', compact('tagihans', 'school'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
        ]);

        $schoolId = Auth::user()->school_id;
        $activeTa = TahunAjaran::where('school_id', $schoolId)->where('is_active', true)->first();

        if (!$activeTa) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum dikonfigurasi.');
        }

        $siswas = Siswa::where('school_id', $schoolId)->where('status', 'Aktif')->get();
        $count = 0;

        foreach ($siswas as $siswa) {
            $existing = TagihanSpp::where('school_id', $schoolId)
                ->where('siswa_id', $siswa->id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->exists();

            if (!$existing) {
                TagihanSpp::create([
                    'school_id' => $schoolId,
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $activeTa->id,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'nominal' => $request->nominal,
                    'potongan' => 0,
                    'total_tagihan' => $request->nominal,
                    'status' => 'Belum Lunas',
                    'jatuh_tempo' => now()->addDays(10),
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "Berhasil membuat {$count} tagihan SPP untuk bulan {$request->bulan} {$request->tahun}.");
    }
}
