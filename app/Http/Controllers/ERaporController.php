<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\NarrativeBank;
use App\Models\Rombel;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ERaporController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $rombels = Rombel::where('school_id', $schoolId)->get();
        $selectedRombelId = $request->get('rombel_id', $rombels->first()?->id);

        $siswas = collect();
        if ($selectedRombelId) {
            $siswas = Siswa::where('school_id', $schoolId)
                ->where('rombel_id', $selectedRombelId)
                ->with('assessments')
                ->get();
        }

        $narrativeBanks = NarrativeBank::where('school_id', $schoolId)->get();

        return view('erapor.index', compact('rombels', 'selectedRombelId', 'siswas', 'narrativeBanks'));
    }

    public function storeAssessment(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'mata_pelajaran' => 'required|string',
            'jenis_penilaian' => 'required|in:Formatif,Sumatif,P5',
            'nilai_angka' => 'nullable|numeric|min:0|max:100',
            'capaian_narasi' => 'required|string',
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);
        $user = Auth::user();

        Assessment::create([
            'school_id' => $user->school_id,
            'rombel_id' => $siswa->rombel_id,
            'siswa_id' => $siswa->id,
            'guru_id' => $user->guru?->id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'jenis_penilaian' => $request->jenis_penilaian,
            'nilai_angka' => $request->nilai_angka,
            'capaian_narasi' => $request->capaian_narasi,
        ]);

        return redirect()->back()->with('success', 'Penilaian E-Rapor berhasil disimpan!');
    }

    public function exportPdf(Siswa $siswa)
    {
        $school = Auth::user()->school;
        $siswa->load(['rombel.waliKelas', 'assessments', 'presensis', 'portfolios']);

        $pdf = Pdf::loadView('erapor.pdf', compact('school', 'siswa'));
        return $pdf->download('E-Rapor_' . str_replace(' ', '_', $siswa->nama_lengkap) . '.pdf');
    }
}
