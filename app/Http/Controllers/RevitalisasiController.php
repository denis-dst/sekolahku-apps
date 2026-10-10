<?php

namespace App\Http\Controllers;

use App\Exports\RevitalisasiExcelExport;
use App\Models\RevitalisasiProyek;
use App\Models\RevitalisasiTransaksi;
use App\Models\RevitalisasiTransaksiItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\School;

class RevitalisasiController extends Controller
{
    /**
     * Dashboard & Project Overview
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperadmin = $user->hasRole('Superadmin');
        $allSchools = $isSuperadmin ? School::orderBy('name')->get() : collect();

        if ($isSuperadmin && $request->filled('school_id')) {
            $school = School::find($request->school_id) ?: School::first();
        } else {
            $school = $user->school ?: School::first();
        }

        $schoolId = $school ? $school->id : null;

        if (!$schoolId) {
            return redirect()->route('dashboard')->with('error', 'Belum ada data sekolah yang terdaftar di sistem.');
        }

        $allProyeks = RevitalisasiProyek::where('school_id', $schoolId)
            ->orderBy('tahun_anggaran', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // If no project exists yet, automatically create one with school defaults
        if ($allProyeks->isEmpty()) {
            $defaultProyek = RevitalisasiProyek::create([
                'school_id' => $schoolId,
                'nama_kegiatan' => 'Bantuan Revitalisasi Sarana dan Prasarana PAUD / P2SP ' . date('Y'),
                'tahun_anggaran' => (int) date('Y'),
                'pagu_anggaran' => 200000000,
                'nama_bank' => 'Bank Mandiri',
                'nomor_rekening' => '123-00-987654-1',
                'atas_nama_rekening' => 'Panitia P2SP ' . ($school->name ?? 'Sekolah'),
                'lokasi_surat' => 'Jakarta',
                'tanggal_tutup_buku' => Carbon::now(),
                'penanggung_jawab_nama' => $school->kepala_sekolah_nama ?? 'Dra. Hj. Nurjanah, M.Pd',
                'penanggung_jawab_nip' => $school->kepala_sekolah_nip ?? '19700512 199503 2 001',
                'ketua_p2sp_nama' => 'Ahmad Suhendar, S.T',
                'ketua_p2sp_nip' => '-',
                'bendahara_nama' => $school->bendahara_nama ?? 'Siti Rahmawati, S.E',
                'bendahara_nip' => $school->bendahara_nip ?? '19850210 201001 2 015',
                'pengawas_nama' => 'Ir. Bambang Trihatmojo',
                'pengawas_nip' => '-',
                'status' => 'Aktif',
            ]);

            $allProyeks = collect([$defaultProyek]);
        }

        // Active selected project
        $selectedId = $request->get('proyek_id', $allProyeks->first()->id);
        $proyek = $allProyeks->firstWhere('id', $selectedId) ?: $allProyeks->first();

        // Query transactions
        $query = $proyek->transaksis()->with(['items', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('uraian', 'like', "%{$search}%")
                  ->orWhere('nomor_bukti', 'like', "%{$search}%")
                  ->orWhere('penerima_vendor', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        if ($request->filled('jenis_biaya')) {
            $query->where('jenis_biaya', $request->jenis_biaya);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        $transaksis = $query->paginate(20)->withQueryString();
        $summary = $proyek->summary;

        return view('revitalisasi.index', compact(
            'allProyeks',
            'proyek',
            'transaksis',
            'summary',
            'school',
            'allSchools',
            'isSuperadmin'
        ));
    }

    /**
     * Buku Kas Umum (BKU) View - Format 2 Sisi (Penerimaan vs Pengeluaran)
     */
    public function bku(Request $request, RevitalisasiProyek $proyek)
    {
        $this->authorizeSchool($proyek);

        $transaksis = $proyek->transaksis()->with(['items'])->get();

        $receipts = [];
        $expenses = [];

        $penerimaanNo = 1;
        $pengeluaranNo = 1;

        foreach ($transaksis as $trx) {
            if ($trx->jenis_transaksi === 'terima_dana') {
                $receipts[] = (object) [
                    'no' => $penerimaanNo++,
                    'tanggal' => $trx->tanggal,
                    'uraian' => $trx->uraian,
                    'nomor_bukti' => $trx->nomor_bukti,
                    'jumlah' => (float) $trx->nominal,
                    'id' => $trx->id,
                ];
            } elseif (in_array($trx->jenis_transaksi, ['belanja_tunai', 'belanja_transfer'])) {
                if ($trx->items->isNotEmpty()) {
                    // Header
                    $expenses[] = (object) [
                        'is_parent' => true,
                        'no' => $pengeluaranNo++,
                        'tanggal' => $trx->tanggal,
                        'uraian' => $trx->uraian,
                        'nomor_bukti' => $trx->nomor_bukti,
                        'jenis_biaya' => '',
                        'jumlah' => null,
                        'id' => $trx->id,
                        'jenis_transaksi' => $trx->jenis_transaksi,
                    ];
                    // Sub-items
                    foreach ($trx->items as $item) {
                        $expenses[] = (object) [
                            'is_parent' => false,
                            'no' => '',
                            'tanggal' => null,
                            'uraian' => '- ' . $item->uraian_item,
                            'nomor_bukti' => '',
                            'jenis_biaya' => $item->jenis_biaya ?: $trx->jenis_biaya,
                            'jumlah' => (float) $item->subtotal,
                            'id' => $trx->id,
                            'jenis_transaksi' => $trx->jenis_transaksi,
                        ];
                    }
                } else {
                    $expenses[] = (object) [
                        'is_parent' => false,
                        'no' => $pengeluaranNo++,
                        'tanggal' => $trx->tanggal,
                        'uraian' => $trx->uraian,
                        'nomor_bukti' => $trx->nomor_bukti,
                        'jenis_biaya' => $trx->jenis_biaya,
                        'jumlah' => (float) $trx->nominal,
                        'id' => $trx->id,
                        'jenis_transaksi' => $trx->jenis_transaksi,
                    ];
                }
            }
        }

        $summary = $proyek->summary;
        $allProyeks = RevitalisasiProyek::where('school_id', $proyek->school_id)->get();

        return view('revitalisasi.bku', compact('proyek', 'receipts', 'expenses', 'summary', 'allProyeks'));
    }

    /**
     * Buku Pembantu Kas Tunai (BPKT) View
     */
    public function bpkt(Request $request, RevitalisasiProyek $proyek)
    {
        $this->authorizeSchool($proyek);

        $transaksis = $proyek->transaksis()->with(['items'])->get();

        $rows = [];
        $runningSaldo = 0;
        $noUrut = 1;

        foreach ($transaksis as $trx) {
            if ($trx->jenis_transaksi === 'tarik_bank') {
                $nom = (float) $trx->nominal;
                $runningSaldo += $nom;
                $rows[] = (object) [
                    'id' => $trx->id,
                    'no' => $noUrut++,
                    'tanggal' => $trx->tanggal,
                    'uraian' => $trx->uraian ?: 'Penarikan tunai',
                    'nomor_bukti' => $trx->nomor_bukti,
                    'penerimaan' => $nom,
                    'pengeluaran' => null,
                    'saldo' => $runningSaldo,
                    'is_subitem' => false,
                ];
            } elseif ($trx->jenis_transaksi === 'belanja_tunai') {
                if ($trx->items->isNotEmpty()) {
                    // Header belanja
                    $rows[] = (object) [
                        'id' => $trx->id,
                        'no' => $noUrut++,
                        'tanggal' => $trx->tanggal,
                        'uraian' => $trx->uraian,
                        'nomor_bukti' => $trx->nomor_bukti,
                        'penerimaan' => null,
                        'pengeluaran' => null,
                        'saldo' => $runningSaldo,
                        'is_subitem' => false,
                        'is_parent' => true,
                    ];

                    foreach ($trx->items as $item) {
                        $subNom = (float) $item->subtotal;
                        $runningSaldo -= $subNom;
                        $rows[] = (object) [
                            'id' => $trx->id,
                            'no' => '',
                            'tanggal' => null,
                            'uraian' => '- ' . $item->uraian_item,
                            'nomor_bukti' => '',
                            'penerimaan' => null,
                            'pengeluaran' => $subNom,
                            'saldo' => $runningSaldo,
                            'is_subitem' => true,
                            'jenis_biaya' => $item->jenis_biaya ?: $trx->jenis_biaya,
                        ];
                    }
                } else {
                    $nom = (float) $trx->nominal;
                    $runningSaldo -= $nom;
                    $rows[] = (object) [
                        'id' => $trx->id,
                        'no' => $noUrut++,
                        'tanggal' => $trx->tanggal,
                        'uraian' => $trx->uraian,
                        'nomor_bukti' => $trx->nomor_bukti,
                        'penerimaan' => null,
                        'pengeluaran' => $nom,
                        'saldo' => $runningSaldo,
                        'is_subitem' => false,
                    ];
                }
            }
        }

        $summary = $proyek->summary;
        $allProyeks = RevitalisasiProyek::where('school_id', $proyek->school_id)->get();

        return view('revitalisasi.bpkt', compact('proyek', 'rows', 'summary', 'allProyeks'));
    }

    /**
     * Buku Bank (BB) View
     */
    public function bb(Request $request, RevitalisasiProyek $proyek)
    {
        $this->authorizeSchool($proyek);

        $transaksis = $proyek->transaksis()->get();

        $rows = [];
        $runningSaldo = 0;
        $noUrut = 1;

        foreach ($transaksis as $trx) {
            $nom = (float) $trx->nominal;

            if ($trx->jenis_transaksi === 'terima_dana') {
                $runningSaldo += $nom;
                $rows[] = (object) [
                    'id' => $trx->id,
                    'no' => $noUrut++,
                    'tanggal' => $trx->tanggal,
                    'uraian' => $trx->uraian,
                    'nomor_bukti' => $trx->nomor_bukti,
                    'penerimaan' => $nom,
                    'pengeluaran' => null,
                    'saldo' => $runningSaldo,
                ];
            } elseif (in_array($trx->jenis_transaksi, ['tarik_bank', 'belanja_transfer'])) {
                $runningSaldo -= $nom;
                $rows[] = (object) [
                    'id' => $trx->id,
                    'no' => $noUrut++,
                    'tanggal' => $trx->tanggal,
                    'uraian' => $trx->uraian,
                    'nomor_bukti' => $trx->nomor_bukti,
                    'penerimaan' => null,
                    'pengeluaran' => $nom,
                    'saldo' => $runningSaldo,
                ];
            }
        }

        $summary = $proyek->summary;
        $allProyeks = RevitalisasiProyek::where('school_id', $proyek->school_id)->get();

        return view('revitalisasi.bb', compact('proyek', 'rows', 'summary', 'allProyeks'));
    }

    /**
     * Store new Project / Periode Revitalisasi
     */
    public function proyekStore(Request $request)
    {
        $user = Auth::user();
        $schoolId = ($user->hasRole('Superadmin') && $request->filled('school_id'))
            ? (int) $request->school_id
            : ($user->school_id ?: (School::first()?->id));

        $validated = $request->validate([
            'school_id' => 'nullable|exists:schools,id',
            'nama_kegiatan' => 'required|string|max:255',
            'tahun_anggaran' => 'required|integer|min:2020|max:2035',
            'nomor_pks' => 'nullable|string|max:255',
            'nomor_sk' => 'nullable|string|max:255',
            'nama_bank' => 'nullable|string|max:100',
            'nomor_rekening' => 'nullable|string|max:100',
            'atas_nama_rekening' => 'nullable|string|max:150',
            'pagu_anggaran' => 'required|numeric|min:0',
            'lokasi_surat' => 'nullable|string|max:100',
            'tanggal_tutup_buku' => 'nullable|date',
            'penanggung_jawab_nama' => 'nullable|string|max:150',
            'penanggung_jawab_nip' => 'nullable|string|max:100',
            'ketua_p2sp_nama' => 'nullable|string|max:150',
            'ketua_p2sp_nip' => 'nullable|string|max:100',
            'bendahara_nama' => 'nullable|string|max:150',
            'bendahara_nip' => 'nullable|string|max:100',
            'pengawas_nama' => 'nullable|string|max:150',
            'pengawas_nip' => 'nullable|string|max:100',
        ]);

        $validated['school_id'] = $schoolId;
        $validated['lokasi_surat'] = $validated['lokasi_surat'] ?: 'Jakarta';

        $proyek = RevitalisasiProyek::create($validated);

        return redirect()->route('revitalisasi.index', ['proyek_id' => $proyek->id, 'school_id' => $schoolId])
            ->with('success', 'Data Proyek Revitalisasi berhasil dibuat.');
    }

    /**
     * Update Project / Periode Revitalisasi
     */
    public function proyekUpdate(Request $request, RevitalisasiProyek $proyek)
    {
        $this->authorizeSchool($proyek);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tahun_anggaran' => 'required|integer|min:2020|max:2035',
            'nomor_pks' => 'nullable|string|max:255',
            'nomor_sk' => 'nullable|string|max:255',
            'nama_bank' => 'nullable|string|max:100',
            'nomor_rekening' => 'nullable|string|max:100',
            'atas_nama_rekening' => 'nullable|string|max:150',
            'pagu_anggaran' => 'required|numeric|min:0',
            'lokasi_surat' => 'nullable|string|max:100',
            'tanggal_tutup_buku' => 'nullable|date',
            'penanggung_jawab_nama' => 'nullable|string|max:150',
            'penanggung_jawab_nip' => 'nullable|string|max:100',
            'ketua_p2sp_nama' => 'nullable|string|max:150',
            'ketua_p2sp_nip' => 'nullable|string|max:100',
            'bendahara_nama' => 'nullable|string|max:150',
            'bendahara_nip' => 'nullable|string|max:100',
            'pengawas_nama' => 'nullable|string|max:150',
            'pengawas_nip' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:Aktif,Selesai,Arsip',
        ]);

        $proyek->update($validated);

        return back()->with('success', 'Pengaturan & Informasi Proyek Revitalisasi berhasil diperbarui.');
    }

    /**
     * Delete Project
     */
    public function proyekDestroy(RevitalisasiProyek $proyek)
    {
        $this->authorizeSchool($proyek);

        $proyek->delete();

        return redirect()->route('revitalisasi.index')
            ->with('success', 'Data Proyek Revitalisasi telah dihapus.');
    }

    /**
     * Store new Transaction with optional multi-items
     */
    public function transaksiStore(Request $request, RevitalisasiProyek $proyek)
    {
        $this->authorizeSchool($proyek);

        $validated = $request->validate([
            'nomor_bukti' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:terima_dana,tarik_bank,belanja_tunai,belanja_transfer',
            'uraian' => 'required|string|max:500',
            'jenis_biaya' => 'nullable|string|in:Bahan,Alat,Upah,Manajemen',
            'penerima_vendor' => 'nullable|string|max:150',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'file_bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'items' => 'nullable|array',
            'items.*.uraian_item' => 'required_with:items|string|max:255',
            'items.*.jenis_biaya' => 'nullable|string|in:Bahan,Alat,Upah,Manajemen',
            'items.*.volume' => 'nullable|numeric|min:0',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga_satuan' => 'nullable|numeric|min:0',
            'items.*.subtotal' => 'required_with:items|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $filePath = null;
            if ($request->hasFile('file_bukti')) {
                $filePath = $request->file('file_bukti')->store('revitalisasi/bukti', 'public');
            }

            $maxUrutan = $proyek->transaksis()->max('urutan') ?? 0;

            $transaksi = RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => $validated['nomor_bukti'],
                'tanggal' => $validated['tanggal'],
                'jenis_transaksi' => $validated['jenis_transaksi'],
                'uraian' => $validated['uraian'],
                'jenis_biaya' => in_array($validated['jenis_transaksi'], ['belanja_tunai', 'belanja_transfer']) ? ($validated['jenis_biaya'] ?? null) : null,
                'penerima_vendor' => $validated['penerima_vendor'] ?? null,
                'nominal' => $validated['nominal'],
                'keterangan' => $validated['keterangan'] ?? null,
                'file_bukti' => $filePath,
                'urutan' => $maxUrutan + 1,
            ]);

            // Save sub items if any
            if (!empty($validated['items'])) {
                $itemIndex = 1;
                $sumItems = 0;
                foreach ($validated['items'] as $itemData) {
                    $subtotal = (float) $itemData['subtotal'];
                    $sumItems += $subtotal;

                    RevitalisasiTransaksiItem::create([
                        'revitalisasi_transaksi_id' => $transaksi->id,
                        'uraian_item' => $itemData['uraian_item'],
                        'jenis_biaya' => $itemData['jenis_biaya'] ?? ($transaksi->jenis_biaya ?: 'Bahan'),
                        'volume' => $itemData['volume'] ?? null,
                        'satuan' => $itemData['satuan'] ?? null,
                        'harga_satuan' => $itemData['harga_satuan'] ?? null,
                        'subtotal' => $subtotal,
                        'urutan' => $itemIndex++,
                    ]);
                }

                if ($sumItems > 0 && $sumItems != $transaksi->nominal) {
                    $transaksi->update(['nominal' => $sumItems]);
                }
            }

            DB::commit();

            return back()->with('success', 'Transaksi No. Bukti ' . $transaksi->nomor_bukti . ' berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Update Transaction
     */
    public function transaksiUpdate(Request $request, RevitalisasiTransaksi $transaksi)
    {
        $this->authorizeSchool($transaksi->proyek);

        $validated = $request->validate([
            'nomor_bukti' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:terima_dana,tarik_bank,belanja_tunai,belanja_transfer',
            'uraian' => 'required|string|max:500',
            'jenis_biaya' => 'nullable|string|in:Bahan,Alat,Upah,Manajemen',
            'penerima_vendor' => 'nullable|string|max:150',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'file_bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|integer',
            'items.*.uraian_item' => 'required_with:items|string|max:255',
            'items.*.jenis_biaya' => 'nullable|string|in:Bahan,Alat,Upah,Manajemen',
            'items.*.volume' => 'nullable|numeric|min:0',
            'items.*.satuan' => 'nullable|string|max:50',
            'items.*.harga_satuan' => 'nullable|numeric|min:0',
            'items.*.subtotal' => 'required_with:items|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('file_bukti')) {
                if ($transaksi->file_bukti && Storage::disk('public')->exists($transaksi->file_bukti)) {
                    Storage::disk('public')->delete($transaksi->file_bukti);
                }
                $transaksi->file_bukti = $request->file('file_bukti')->store('revitalisasi/bukti', 'public');
            }

            $transaksi->update([
                'nomor_bukti' => $validated['nomor_bukti'],
                'tanggal' => $validated['tanggal'],
                'jenis_transaksi' => $validated['jenis_transaksi'],
                'uraian' => $validated['uraian'],
                'jenis_biaya' => in_array($validated['jenis_transaksi'], ['belanja_tunai', 'belanja_transfer']) ? ($validated['jenis_biaya'] ?? null) : null,
                'penerima_vendor' => $validated['penerima_vendor'] ?? null,
                'nominal' => $validated['nominal'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Sync sub-items
            if (isset($validated['items']) && is_array($validated['items'])) {
                $transaksi->items()->delete();
                $itemIndex = 1;
                $sumItems = 0;
                foreach ($validated['items'] as $itemData) {
                    $subtotal = (float) $itemData['subtotal'];
                    $sumItems += $subtotal;

                    RevitalisasiTransaksiItem::create([
                        'revitalisasi_transaksi_id' => $transaksi->id,
                        'uraian_item' => $itemData['uraian_item'],
                        'jenis_biaya' => $itemData['jenis_biaya'] ?? ($transaksi->jenis_biaya ?: 'Bahan'),
                        'volume' => $itemData['volume'] ?? null,
                        'satuan' => $itemData['satuan'] ?? null,
                        'harga_satuan' => $itemData['harga_satuan'] ?? null,
                        'subtotal' => $subtotal,
                        'urutan' => $itemIndex++,
                    ]);
                }

                if ($sumItems > 0 && $sumItems != $transaksi->nominal) {
                    $transaksi->update(['nominal' => $sumItems]);
                }
            } else {
                $transaksi->items()->delete();
            }

            DB::commit();

            return back()->with('success', 'Transaksi No. Bukti ' . $transaksi->nomor_bukti . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Delete Transaction
     */
    public function transaksiDestroy(RevitalisasiTransaksi $transaksi)
    {
        $this->authorizeSchool($transaksi->proyek);

        if ($transaksi->file_bukti && Storage::disk('public')->exists($transaksi->file_bukti)) {
            Storage::disk('public')->delete($transaksi->file_bukti);
        }

        $noBukti = $transaksi->nomor_bukti;
        $transaksi->delete();

        return back()->with('success', 'Transaksi No. Bukti ' . $noBukti . ' berhasil dihapus.');
    }

    /**
     * Cetak Kuitansi Resmi Transaksi
     */
    public function kuitansi(RevitalisasiTransaksi $transaksi)
    {
        $this->authorizeSchool($transaksi->proyek);

        $proyek = $transaksi->proyek;
        $school = $proyek->school;
        $terbilang = $this->terbilang((int) $transaksi->nominal) . ' Rupiah';

        return view('revitalisasi.kuitansi', compact('transaksi', 'proyek', 'school', 'terbilang'));
    }

    /**
     * Export Excel (.xlsx) dengan 3 Sheet: BKU, BPKT, BB
     */
    public function exportExcel(RevitalisasiProyek $proyek)
    {
        $this->authorizeSchool($proyek);

        $export = new RevitalisasiExcelExport($proyek);
        $filename = 'Simulasi_Pembukuan_Revitalisasi_' . $proyek->tahun_anggaran . '_' . date('Ymd_His') . '.xlsx';

        return $export->download($filename);
    }

    /**
     * Export PDF untuk BKU / BPKT / BB
     */
    public function exportPdf(Request $request, RevitalisasiProyek $proyek)
    {
        $this->authorizeSchool($proyek);

        $type = $request->get('type', 'bku'); // bku, bpkt, bb, all
        $school = $proyek->school;
        $summary = $proyek->summary;

        if ($type === 'bpkt') {
            $transaksis = $proyek->transaksis()->with(['items'])->get();
            $rows = [];
            $runningSaldo = 0;
            $noUrut = 1;
            foreach ($transaksis as $trx) {
                if ($trx->jenis_transaksi === 'tarik_bank') {
                    $nom = (float) $trx->nominal;
                    $runningSaldo += $nom;
                    $rows[] = (object) [
                        'no' => $noUrut++,
                        'tanggal' => $trx->tanggal,
                        'uraian' => $trx->uraian ?: 'Penarikan tunai',
                        'nomor_bukti' => $trx->nomor_bukti,
                        'penerimaan' => $nom,
                        'pengeluaran' => null,
                        'saldo' => $runningSaldo,
                        'is_subitem' => false,
                    ];
                } elseif ($trx->jenis_transaksi === 'belanja_tunai') {
                    if ($trx->items->isNotEmpty()) {
                        $rows[] = (object) [
                            'no' => $noUrut++,
                            'tanggal' => $trx->tanggal,
                            'uraian' => $trx->uraian,
                            'nomor_bukti' => $trx->nomor_bukti,
                            'penerimaan' => null,
                            'pengeluaran' => null,
                            'saldo' => $runningSaldo,
                            'is_subitem' => false,
                            'is_parent' => true,
                        ];
                        foreach ($trx->items as $item) {
                            $subNom = (float) $item->subtotal;
                            $runningSaldo -= $subNom;
                            $rows[] = (object) [
                                'no' => '',
                                'tanggal' => null,
                                'uraian' => '- ' . $item->uraian_item,
                                'nomor_bukti' => '',
                                'penerimaan' => null,
                                'pengeluaran' => $subNom,
                                'saldo' => $runningSaldo,
                                'is_subitem' => true,
                                'jenis_biaya' => $item->jenis_biaya ?: $trx->jenis_biaya,
                            ];
                        }
                    } else {
                        $nom = (float) $trx->nominal;
                        $runningSaldo -= $nom;
                        $rows[] = (object) [
                            'no' => $noUrut++,
                            'tanggal' => $trx->tanggal,
                            'uraian' => $trx->uraian,
                            'nomor_bukti' => $trx->nomor_bukti,
                            'penerimaan' => null,
                            'pengeluaran' => $nom,
                            'saldo' => $runningSaldo,
                            'is_subitem' => false,
                        ];
                    }
                }
            }

            $pdf = Pdf::loadView('revitalisasi.pdf_bpkt', compact('proyek', 'school', 'rows', 'summary'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('Buku_Pembantu_Kas_Tunai_BPKT_' . $proyek->tahun_anggaran . '.pdf');
        } elseif ($type === 'bb') {
            $transaksis = $proyek->transaksis()->get();
            $rows = [];
            $runningSaldo = 0;
            $noUrut = 1;
            foreach ($transaksis as $trx) {
                $nom = (float) $trx->nominal;
                if ($trx->jenis_transaksi === 'terima_dana') {
                    $runningSaldo += $nom;
                    $rows[] = (object) [
                        'no' => $noUrut++,
                        'tanggal' => $trx->tanggal,
                        'uraian' => $trx->uraian,
                        'nomor_bukti' => $trx->nomor_bukti,
                        'penerimaan' => $nom,
                        'pengeluaran' => null,
                        'saldo' => $runningSaldo,
                    ];
                } elseif (in_array($trx->jenis_transaksi, ['tarik_bank', 'belanja_transfer'])) {
                    $runningSaldo -= $nom;
                    $rows[] = (object) [
                        'no' => $noUrut++,
                        'tanggal' => $trx->tanggal,
                        'uraian' => $trx->uraian,
                        'nomor_bukti' => $trx->nomor_bukti,
                        'penerimaan' => null,
                        'pengeluaran' => $nom,
                        'saldo' => $runningSaldo,
                    ];
                }
            }

            $pdf = Pdf::loadView('revitalisasi.pdf_bb', compact('proyek', 'school', 'rows', 'summary'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('Buku_Bank_BB_' . $proyek->tahun_anggaran . '.pdf');
        } else {
            // Default BKU
            $transaksis = $proyek->transaksis()->with(['items'])->get();
            $receipts = [];
            $expenses = [];
            $penerimaanNo = 1;
            $pengeluaranNo = 1;

            foreach ($transaksis as $trx) {
                if ($trx->jenis_transaksi === 'terima_dana') {
                    $receipts[] = (object) [
                        'no' => $penerimaanNo++,
                        'tanggal' => $trx->tanggal,
                        'uraian' => $trx->uraian,
                        'nomor_bukti' => $trx->nomor_bukti,
                        'jumlah' => (float) $trx->nominal,
                    ];
                } elseif (in_array($trx->jenis_transaksi, ['belanja_tunai', 'belanja_transfer'])) {
                    if ($trx->items->isNotEmpty()) {
                        $expenses[] = (object) [
                            'is_parent' => true,
                            'no' => $pengeluaranNo++,
                            'tanggal' => $trx->tanggal,
                            'uraian' => $trx->uraian,
                            'nomor_bukti' => $trx->nomor_bukti,
                            'jenis_biaya' => '',
                            'jumlah' => null,
                        ];
                        foreach ($trx->items as $item) {
                            $expenses[] = (object) [
                                'is_parent' => false,
                                'no' => '',
                                'tanggal' => null,
                                'uraian' => '- ' . $item->uraian_item,
                                'nomor_bukti' => '',
                                'jenis_biaya' => $item->jenis_biaya ?: $trx->jenis_biaya,
                                'jumlah' => (float) $item->subtotal,
                            ];
                        }
                    } else {
                        $expenses[] = (object) [
                            'is_parent' => false,
                            'no' => $pengeluaranNo++,
                            'tanggal' => $trx->tanggal,
                            'uraian' => $trx->uraian,
                            'nomor_bukti' => $trx->nomor_bukti,
                            'jenis_biaya' => $trx->jenis_biaya,
                            'jumlah' => (float) $trx->nominal,
                        ];
                    }
                }
            }

            $pdf = Pdf::loadView('revitalisasi.pdf_bku', compact('proyek', 'school', 'receipts', 'expenses', 'summary'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('Buku_Kas_Umum_BKU_' . $proyek->tahun_anggaran . '.pdf');
        }
    }

    /**
     * One-click Seed Data Simulasi Persis File 05a_Simulasi Pembukuan.xlsx
     */
    public function seedSimulasi(RevitalisasiProyek $proyek)
    {
        $this->authorizeSchool($proyek);

        DB::beginTransaction();
        try {
            // Remove previous transactions
            $proyek->transaksis()->delete();

            $thn = $proyek->tahun_anggaran ?: 2026;

            // 1. Terima termin 1 dari Direktorat PAUD Rp 200.000.000 (No Bukti 1)
            RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => '1',
                'tanggal' => Carbon::createFromDate($thn, 10, 1),
                'jenis_transaksi' => 'terima_dana',
                'uraian' => 'Terima termin 1 dari Direktortat PAUD',
                'jenis_biaya' => null,
                'penerima_vendor' => 'Direktorat PAUD Kemendikbudristek',
                'nominal' => 200000000,
                'keterangan' => 'Pencairan Dana Tahap I Bantuan Revitalisasi Sarpras',
                'urutan' => 1,
            ]);

            // 2. Penarikan tunai Rp 50.000.000 (No Bukti 2)
            RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => '2',
                'tanggal' => Carbon::createFromDate($thn, 10, 2),
                'jenis_transaksi' => 'tarik_bank',
                'uraian' => 'Penarikan tunai',
                'jenis_biaya' => null,
                'penerima_vendor' => 'Bendahara Panitia P2SP',
                'nominal' => 50000000,
                'keterangan' => 'Penarikan Uang Persediaan (UP) dari Bank Mandiri ke Kas Tunai',
                'urutan' => 2,
            ]);

            // 3. Bayar konsumsi rapat P2SP Rp 150.000 (No Bukti 3)
            RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => '3',
                'tanggal' => Carbon::createFromDate($thn, 10, 2),
                'jenis_transaksi' => 'belanja_tunai',
                'uraian' => 'Bayar konsumsi rapat P2SP',
                'jenis_biaya' => 'Manajemen',
                'penerima_vendor' => 'Katering Ibu Siti',
                'nominal' => 150000,
                'keterangan' => 'Konsumsi rapat koordinasi panitia pembangunan dan komite',
                'urutan' => 3,
            ]);

            // 4. Belanja material di TB ABC Rp 10.800.000 (No Bukti 4) with 4 items
            $trx4 = RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => '4',
                'tanggal' => Carbon::createFromDate($thn, 10, 2),
                'jenis_transaksi' => 'belanja_tunai',
                'uraian' => 'Belanja material di TB ABC',
                'jenis_biaya' => 'Bahan',
                'penerima_vendor' => 'Toko Bangunan ABC',
                'nominal' => 10800000,
                'keterangan' => 'Pengadaan semen, bata merah, gerobak dorong, dan pasir',
                'urutan' => 4,
            ]);

            RevitalisasiTransaksiItem::create([
                'revitalisasi_transaksi_id' => $trx4->id,
                'uraian_item' => 'semen 100sak @70,000',
                'jenis_biaya' => 'Bahan',
                'volume' => 100,
                'satuan' => 'sak',
                'harga_satuan' => 70000,
                'subtotal' => 7000000,
                'urutan' => 1,
            ]);

            RevitalisasiTransaksiItem::create([
                'revitalisasi_transaksi_id' => $trx4->id,
                'uraian_item' => 'bata merah 1000 buah @1.500',
                'jenis_biaya' => 'Bahan',
                'volume' => 1000,
                'satuan' => 'buah',
                'harga_satuan' => 1500,
                'subtotal' => 1500000,
                'urutan' => 2,
            ]);

            RevitalisasiTransaksiItem::create([
                'revitalisasi_transaksi_id' => $trx4->id,
                'uraian_item' => 'Gerobak dorong 2 buah @400.000',
                'jenis_biaya' => 'Alat',
                'volume' => 2,
                'satuan' => 'buah',
                'harga_satuan' => 400000,
                'subtotal' => 800000,
                'urutan' => 3,
            ]);

            RevitalisasiTransaksiItem::create([
                'revitalisasi_transaksi_id' => $trx4->id,
                'uraian_item' => 'pasir 1 truk @1.500.000',
                'jenis_biaya' => 'Bahan',
                'volume' => 1,
                'satuan' => 'truk',
                'harga_satuan' => 1500000,
                'subtotal' => 1500000,
                'urutan' => 4,
            ]);

            // 5. Pembayaran Upah Rp 2.500.000 (No Bukti 5)
            RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => '5',
                'tanggal' => Carbon::createFromDate($thn, 10, 10),
                'jenis_transaksi' => 'belanja_tunai',
                'uraian' => 'Pembayaran Upah',
                'jenis_biaya' => 'Upah',
                'penerima_vendor' => 'Mandor & Tukang Bangunan',
                'nominal' => 2500000,
                'keterangan' => 'Pembayaran upah tukang periode minggu ke-1',
                'urutan' => 5,
            ]);

            // 6. Sewa Molen di CV DEF (transfer) Rp 2.000.000 (No Bukti 6)
            RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => '6',
                'tanggal' => Carbon::createFromDate($thn, 10, 11),
                'jenis_transaksi' => 'belanja_transfer',
                'uraian' => 'Sewa Molen di CV DEF (transfer)',
                'jenis_biaya' => 'Alat',
                'penerima_vendor' => 'CV DEF Rental Alat',
                'nominal' => 2000000,
                'keterangan' => 'Sewa 1 unit mesin molen cor semen via transfer bank',
                'urutan' => 6,
            ]);

            // 7. Pembayaran Honor Panitia Rp 2.500.000 (No Bukti 7)
            RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => '7',
                'tanggal' => Carbon::createFromDate($thn, 10, 29),
                'jenis_transaksi' => 'belanja_tunai',
                'uraian' => 'Pembayaran Honor Panitia',
                'jenis_biaya' => 'Manajemen',
                'penerima_vendor' => 'Panitia P2SP',
                'nominal' => 2500000,
                'keterangan' => 'Honor panitia pembangunan P2SP sesuai SK',
                'urutan' => 7,
            ]);

            // 8. Pembayaran Honor Perencana Rp 2.500.000 (No Bukti 8)
            RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => '8',
                'tanggal' => Carbon::createFromDate($thn, 10, 29),
                'jenis_transaksi' => 'belanja_tunai',
                'uraian' => 'Pembayaran Honor Perencana',
                'jenis_biaya' => 'Manajemen',
                'penerima_vendor' => 'Tim Perencana Teknis',
                'nominal' => 2500000,
                'keterangan' => 'Honor perencanaan teknis dan gambar arsitektur',
                'urutan' => 8,
            ]);

            // 9. Pembayaran Honor Pengawas Rp 2.500.000 (No Bukti 9)
            RevitalisasiTransaksi::create([
                'school_id' => $proyek->school_id,
                'revitalisasi_proyek_id' => $proyek->id,
                'user_id' => Auth::id(),
                'nomor_bukti' => '9',
                'tanggal' => Carbon::createFromDate($thn, 10, 29),
                'jenis_transaksi' => 'belanja_tunai',
                'uraian' => 'Pembayaran Honor Pengawas',
                'jenis_biaya' => 'Manajemen',
                'penerima_vendor' => 'Tim Pengawas Lapangan',
                'nominal' => 2500000,
                'keterangan' => 'Honor pengawasan kualitas dan progres pekerjaan lapangan',
                'urutan' => 9,
            ]);

            $proyek->update([
                'pagu_anggaran' => 200000000,
                'tanggal_tutup_buku' => Carbon::createFromDate($thn, 10, 31),
            ]);

            DB::commit();

            return redirect()->route('revitalisasi.index', ['proyek_id' => $proyek->id])
                ->with('success', 'Data Simulasi Pembukuan Revitalisasi berhasil dimuat. Total Penerimaan: Rp 200.000.000, Pengeluaran BKU: Rp 22.950.000, Saldo Kas Umum: Rp 177.050.000 (Kas Tunai: Rp 29.050.000, Bank: Rp 148.000.000).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memuat data simulasi: ' . $e->getMessage());
        }
    }

    private function authorizeSchool(RevitalisasiProyek $proyek): void
    {
        $schoolId = Auth::user()->school_id;
        if ($proyek->school_id !== $schoolId && !Auth::user()->hasRole('Superadmin')) {
            abort(403, 'Akses ditolak ke data proyek sekolah ini.');
        }
    }

    /**
     * Konversi angka rupiah ke kalimat terbilang Indonesia
     */
    private function terbilang(int $angka): string
    {
        $angka = abs($angka);
        $baca = [
            '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'
        ];

        if ($angka < 12) {
            return $baca[$angka];
        } elseif ($angka < 20) {
            return $this->terbilang($angka - 10) . ' Belas';
        } elseif ($angka < 100) {
            return $this->terbilang(intdiv($angka, 10)) . ' Puluh ' . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            return 'Seratus ' . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return $this->terbilang(intdiv($angka, 100)) . ' Ratus ' . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return 'Seribu ' . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return $this->terbilang(intdiv($angka, 1000)) . ' Ribu ' . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            return $this->terbilang(intdiv($angka, 1000000)) . ' Juta ' . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            return $this->terbilang(intdiv($angka, 1000000000)) . ' Miliar ' . $this->terbilang(fmod($angka, 1000000000));
        }

        return (string) $angka;
    }
}
