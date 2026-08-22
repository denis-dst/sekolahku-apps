<?php

namespace App\Http\Controllers;

use App\Models\DanaBosp;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ExpenseReceipt;
use App\Models\ExpenseStatusHistory;
use App\Models\Reimbursement;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExpenseController extends Controller
{
    /**
     * Display list of expenses with filters, KPI metrics, and Dana BOSP Awal Cair per Period
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $categories = ExpenseCategory::where('school_id', $schoolId)->get();

        $query = Expense::where('school_id', $schoolId)
            ->with(['user:id,name', 'category:id,nama_kategori,kode_bosp', 'receipts', 'statusHistories']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('uraian', 'like', "%{$search}%")
                  ->orWhere('toko_vendor', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $totalTalangan = Expense::where('school_id', $schoolId)->sum('nominal');
        $totalPending = Expense::where('school_id', $schoolId)->whereIn('status', ['Belum Diajukan', 'Diajukan'])->sum('nominal');
        $totalDibayar = Expense::where('school_id', $schoolId)->where('status', 'Dibayar')->sum('nominal');

        // BOSP Dana Awal Cair per Periode
        $currentYear = (int) $request->get('bosp_year', now()->year);
        $defaultPeriode = (now()->month <= 6) ? 'Tahap 1 (Semester I)' : 'Tahap 2 (Semester II)';
        $currentPeriode = $request->get('bosp_periode', $defaultPeriode);

        $danaBosp = DanaBosp::where('school_id', $schoolId)
            ->where('tahun', $currentYear)
            ->where('periode', $currentPeriode)
            ->first();

        $allDanaBosps = DanaBosp::where('school_id', $schoolId)
            ->orderBy('tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        [$startDate, $endDate] = $this->calculatePeriodDates($currentYear, $currentPeriode);

        // 1. Pengeluaran talangan yang SUDAH DIGANTI (status 'Dibayar') pada periode terpilih (riil keluar dari kas BOSP)
        $talanganDigantiPeriode = Expense::where('school_id', $schoolId)
            ->where('status', 'Dibayar')
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('nominal');

        // 2. Pengeluaran talangan yang BELUM DIGANTI (status 'Belum Diajukan', 'Diajukan', 'Disetujui') pada periode terpilih
        $talanganPendingPeriode = Expense::where('school_id', $schoolId)
            ->whereIn('status', ['Belum Diajukan', 'Diajukan', 'Disetujui'])
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('nominal');

        // 3. Total seluruh catatan talangan pada periode terpilih
        $totalBelanjaPeriode = Expense::where('school_id', $schoolId)
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('nominal');

        $nominalDanaBosp = $danaBosp ? (float) $danaBosp->nominal_cair : 0;

        // 4. Saldo Terkini Kas BOSP = Dana BOSP Cair - Pengeluaran Talangan yang Sudah Diganti (Reimburse)
        $saldoTerkiniBosp = $nominalDanaBosp - $talanganDigantiPeriode;

        // 5. Estimasi sisa saldo jika semua talangan pending dicairkan
        $estimasiSisaSaldo = $nominalDanaBosp - $totalBelanjaPeriode;

        // 6. Persentase serapan kas BOSP riil (dari talangan yang sudah dibayarkan/diganti)
        $persentaseSerapan = $nominalDanaBosp > 0 ? min(100, round(($talanganDigantiPeriode / $nominalDanaBosp) * 100, 1)) : 0;
        $persentasePending = $nominalDanaBosp > 0 ? min(100 - $persentaseSerapan, round(($talanganPendingPeriode / $nominalDanaBosp) * 100, 1)) : 0;
        $persentaseKomitmen = $nominalDanaBosp > 0 ? min(100, round(($totalBelanjaPeriode / $nominalDanaBosp) * 100, 1)) : 0;

        // Backwards-compatible aliases
        $realisasiPeriode = $talanganDigantiPeriode;
        $sisaSaldoBosp = $saldoTerkiniBosp;

        return view('expenses.index', compact(
            'categories',
            'expenses',
            'totalTalangan',
            'totalPending',
            'totalDibayar',
            'danaBosp',
            'allDanaBosps',
            'currentYear',
            'currentPeriode',
            'nominalDanaBosp',
            'realisasiPeriode',
            'talanganDigantiPeriode',
            'talanganPendingPeriode',
            'totalBelanjaPeriode',
            'saldoTerkiniBosp',
            'sisaSaldoBosp',
            'estimasiSisaSaldo',
            'persentaseSerapan',
            'persentasePending',
            'persentaseKomitmen',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Store / Update Dana BOSP Awal Cair per Periode
     */
    public function storeDanaBosp(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        if ($request->has('nominal_cair')) {
            $request->merge(['nominal_cair' => preg_replace('/[^0-9]/', '', (string)$request->nominal_cair)]);
        }

        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2099',
            'periode' => 'required|string',
            'nominal_cair' => 'required|numeric|min:0',
            'tanggal_cair' => 'nullable|date',
            'sumber_dana' => 'required|string|max:100',
            'catatan' => 'nullable|string|max:500',
        ]);

        DanaBosp::updateOrCreate(
            [
                'school_id' => $schoolId,
                'tahun' => $request->tahun,
                'periode' => $request->periode,
            ],
            [
                'nominal_cair' => $request->nominal_cair,
                'tanggal_cair' => $request->tanggal_cair ?: now(),
                'sumber_dana' => $request->sumber_dana,
                'catatan' => $request->catatan,
            ]
        );

        return redirect()->route('expenses.index', [
            'bosp_year' => $request->tahun,
            'bosp_periode' => $request->periode,
        ])->with('success', "Informasi Dana BOSP Awal Cair ({$request->periode} {$request->tahun}) sebesar Rp " . number_format($request->nominal_cair, 0, ',', '.') . " berhasil disimpan!");
    }

    /**
     * Helper to compute date range from period
     */
    protected function calculatePeriodDates(int $year, string $periode): array
    {
        if (str_contains($periode, 'Tahap 1') || str_contains($periode, 'Semester I')) {
            return [
                Carbon::createFromDate($year, 1, 1)->startOfDay(),
                Carbon::createFromDate($year, 6, 30)->endOfDay()
            ];
        } elseif (str_contains($periode, 'Tahap 2') || str_contains($periode, 'Semester II')) {
            return [
                Carbon::createFromDate($year, 7, 1)->startOfDay(),
                Carbon::createFromDate($year, 12, 31)->endOfDay()
            ];
        } elseif (str_contains($periode, 'Triwulan 1')) {
            return [
                Carbon::createFromDate($year, 1, 1)->startOfDay(),
                Carbon::createFromDate($year, 3, 31)->endOfDay()
            ];
        } elseif (str_contains($periode, 'Triwulan 2')) {
            return [
                Carbon::createFromDate($year, 4, 1)->startOfDay(),
                Carbon::createFromDate($year, 6, 30)->endOfDay()
            ];
        } elseif (str_contains($periode, 'Triwulan 3')) {
            return [
                Carbon::createFromDate($year, 7, 1)->startOfDay(),
                Carbon::createFromDate($year, 9, 30)->endOfDay()
            ];
        } elseif (str_contains($periode, 'Triwulan 4')) {
            return [
                Carbon::createFromDate($year, 10, 1)->startOfDay(),
                Carbon::createFromDate($year, 12, 31)->endOfDay()
            ];
        } else {
            return [
                Carbon::createFromDate($year, 1, 1)->startOfDay(),
                Carbon::createFromDate($year, 12, 31)->endOfDay()
            ];
        }
    }

    /**
     * Fast Entry Talangan (< 30 seconds)
     */
    public function store(Request $request)
    {
        if ($request->has('nominal')) {
            $request->merge(['nominal' => preg_replace('/[^0-9]/', '', (string)$request->nominal)]);
        }

        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:1',
            'uraian' => 'required|string|max:1000',
            'toko_vendor' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'receipts.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf,heic|max:5120',
        ]);

        $user = Auth::user();

        $expense = Expense::create([
            'school_id' => $user->school_id,
            'user_id' => $user->id,
            'expense_category_id' => $request->expense_category_id,
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'uraian' => $request->uraian,
            'toko_vendor' => $request->toko_vendor,
            'lokasi' => $request->lokasi,
            'status' => 'Belum Diajukan',
        ]);

        // Create status log history
        ExpenseStatusHistory::create([
            'expense_id' => $expense->id,
            'user_id' => $user->id,
            'status_sebelum' => null,
            'status_sesudah' => 'Belum Diajukan',
            'catatan' => 'Pencatatan awal pengeluaran talangan pribadi.',
        ]);

        // Upload Receipts
        if ($request->hasFile('receipts')) {
            foreach ($request->file('receipts') as $file) {
                $path = $file->store('receipts/' . $user->school_id, 'public');
                $extension = strtolower($file->getClientOriginalExtension());
                $fileType = ($extension === 'pdf') ? 'pdf' : $file->getClientMimeType();

                ExpenseReceipt::create([
                    'expense_id' => $expense->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $fileType,
                ]);
            }
        }

        return redirect()->route('expenses.show', $expense->id)->with('success', 'Talangan pribadi berhasil dicatat!');
    }

    /**
     * Show detail view with receipts and audit timeline
     */
    public function show($id)
    {
        $schoolId = Auth::user()->school_id;
        $expense = Expense::with(['category', 'user', 'receipts', 'statusHistories.user'])
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        return view('expenses.show', compact('expense'));
    }

    /**
     * Edit expense form
     */
    public function edit($id)
    {
        $schoolId = Auth::user()->school_id;
        $expense = Expense::where('school_id', $schoolId)->findOrFail($id);
        $categories = ExpenseCategory::where('school_id', $schoolId)->get();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update expense data
     */
    public function update(Request $request, $id)
    {
        $schoolId = Auth::user()->school_id;
        $expense = Expense::where('school_id', $schoolId)->findOrFail($id);

        if ($request->has('nominal')) {
            $request->merge(['nominal' => preg_replace('/[^0-9]/', '', (string)$request->nominal)]);
        }

        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:1',
            'uraian' => 'required|string|max:1000',
            'toko_vendor' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'receipts.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf,heic|max:5120',
        ]);

        $expense->update([
            'expense_category_id' => $request->expense_category_id,
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'uraian' => $request->uraian,
            'toko_vendor' => $request->toko_vendor,
            'lokasi' => $request->lokasi,
        ]);

        // Upload additional receipts
        if ($request->hasFile('receipts')) {
            foreach ($request->file('receipts') as $file) {
                $path = $file->store('receipts/' . $schoolId, 'public');
                $extension = strtolower($file->getClientOriginalExtension());
                $fileType = ($extension === 'pdf') ? 'pdf' : $file->getClientMimeType();

                ExpenseReceipt::create([
                    'expense_id' => $expense->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $fileType,
                ]);
            }
        }

        return redirect()->route('expenses.show', $expense->id)->with('success', 'Data pengeluaran talangan berhasil diperbarui.');
    }

    /**
     * Update Status / Approve / Reimburse Workflow
     */
    public function updateStatus(Request $request, $id)
    {
        $schoolId = Auth::user()->school_id;
        $expense = Expense::where('school_id', $schoolId)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:Belum Diajukan,Diajukan,Disetujui,Dibayar,Ditolak',
            'catatan' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $statusSebelum = $expense->status;
        $statusSesudah = $request->status;

        if ($statusSebelum !== $statusSesudah) {
            $expense->update(['status' => $statusSesudah]);

            ExpenseStatusHistory::create([
                'expense_id' => $expense->id,
                'user_id' => $user->id,
                'status_sebelum' => $statusSebelum,
                'status_sesudah' => $statusSesudah,
                'catatan' => $request->catatan ?: ("Status diubah menjadi " . $statusSesudah),
            ]);

            if ($statusSesudah === 'Dibayar') {
                Reimbursement::updateOrCreate(
                    [
                        'school_id' => $expense->school_id,
                        'expense_id' => $expense->id,
                    ],
                    [
                        'user_id' => $user->id,
                        'nominal_reimburse' => $expense->nominal,
                        'tanggal_pencairan' => now(),
                        'metode_transfer' => 'Cash / Transfer Bank',
                        'catatan' => $request->catatan ?: 'Pencairan reimbursement dana BOSP',
                    ]
                );
            }
        }

        return redirect()->back()->with('success', "Status pengeluaran berhasil diperbarui menjadi: {$statusSesudah}");
    }

    /**
     * Delete expense and remove attached receipt files
     */
    public function destroy($id)
    {
        $schoolId = Auth::user()->school_id;
        $expense = Expense::where('school_id', $schoolId)->findOrFail($id);

        // Delete receipt files from storage
        foreach ($expense->receipts as $receipt) {
            if ($receipt->file_path && !str_starts_with($receipt->file_path, 'http')) {
                Storage::disk('public')->delete($receipt->file_path);
            }
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Catatan pengeluaran talangan telah dihapus.');
    }

    /**
     * Rekapitulasi Periode & Laporan LPJ BOSP
     */
    public function report(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $filterType = $request->get('filter_type', 'month');
        $year = (int) $request->get('year', Carbon::now()->year);
        $month = (int) $request->get('month', Carbon::now()->month);
        $quarter = (int) $request->get('quarter', 1);
        $semester = (int) $request->get('semester', 1);
        $statusFilter = $request->get('status', 'all');

        $query = Expense::with(['category', 'user', 'receipts'])
            ->where('school_id', $schoolId);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $startDate = null;
        $endDate = null;
        $periodLabel = '';

        if ($filterType === 'month') {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $periodLabel = $startDate->translatedFormat('F Y');
        } elseif ($filterType === 'quarter') {
            switch ($quarter) {
                case 1:
                    $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 3, 31)->endOfDay();
                    $periodLabel = "Triwulan I (Januari - Maret) $year";
                    break;
                case 2:
                    $startDate = Carbon::createFromDate($year, 4, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 6, 30)->endOfDay();
                    $periodLabel = "Triwulan II (April - Juni) $year";
                    break;
                case 3:
                    $startDate = Carbon::createFromDate($year, 7, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 9, 30)->endOfDay();
                    $periodLabel = "Triwulan III (Juli - September) $year";
                    break;
                case 4:
                    $startDate = Carbon::createFromDate($year, 10, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
                    $periodLabel = "Triwulan IV (Oktober - Desember) $year";
                    break;
            }
        } elseif ($filterType === 'semester') {
            if ($semester == 1) {
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
                $endDate = Carbon::createFromDate($year, 6, 30)->endOfDay();
                $periodLabel = "Semester I (Januari - Juni) $year";
            } else {
                $startDate = Carbon::createFromDate($year, 7, 1)->startOfDay();
                $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
                $periodLabel = "Semester II (Juli - Desember) $year";
            }
        } elseif ($filterType === 'year') {
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            $periodLabel = "Tahun $year";
        } elseif ($filterType === 'custom') {
            $startDate = Carbon::parse($request->get('date_from', Carbon::now()->startOfMonth()->toDateString()))->startOfDay();
            $endDate = Carbon::parse($request->get('date_to', Carbon::now()->endOfMonth()->toDateString()))->endOfDay();
            $periodLabel = $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y');
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        $expenses = $query->orderBy('tanggal', 'asc')->get();

        // Group total per category
        $categoryTotals = $expenses->groupBy('expense_category_id')->map(function ($group) {
            return [
                'name' => $group->first()->category->nama_kategori ?? 'Lainnya',
                'kode' => $group->first()->category->kode_bosp ?? 'BOSP',
                'count' => $group->count(),
                'total' => $group->sum('nominal'),
            ];
        });

        $totalAmount = $expenses->sum('nominal');

        $bospPeriodeName = match($filterType) {
            'semester' => ($semester == 1 ? 'Tahap 1 (Semester I)' : 'Tahap 2 (Semester II)'),
            'quarter' => "Triwulan $quarter",
            'year' => 'Tahunan',
            default => ($month <= 6 ? 'Tahap 1 (Semester I)' : 'Tahap 2 (Semester II)'),
        };

        $danaBosp = DanaBosp::where('school_id', $schoolId)
            ->where('tahun', $year)
            ->where('periode', $bospPeriodeName)
            ->first();

        return view('expenses.report', compact(
            'expenses',
            'categoryTotals',
            'totalAmount',
            'periodLabel',
            'filterType',
            'year',
            'month',
            'quarter',
            'semester',
            'statusFilter',
            'danaBosp'
        ));
    }

    /**
     * Export Standard LPJ BOSP Report to PDF with Receipt Attachments
     */
    public function exportPdf(Request $request)
    {
        $school = Auth::user()->school;
        $schoolId = $school->id;

        $filterType = $request->get('filter_type', 'month');
        $year = (int) $request->get('year', Carbon::now()->year);
        $month = (int) $request->get('month', Carbon::now()->month);
        $quarter = (int) $request->get('quarter', 1);
        $semester = (int) $request->get('semester', 1);
        $statusFilter = $request->get('status', 'all');

        $query = Expense::with(['category', 'user', 'receipts'])
            ->where('school_id', $schoolId);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $startDate = null;
        $endDate = null;
        $periodLabel = '';

        if ($filterType === 'month') {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $periodLabel = $startDate->translatedFormat('F Y');
        } elseif ($filterType === 'quarter') {
            switch ($quarter) {
                case 1:
                    $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 3, 31)->endOfDay();
                    $periodLabel = "Triwulan I (Januari - Maret) $year";
                    break;
                case 2:
                    $startDate = Carbon::createFromDate($year, 4, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 6, 30)->endOfDay();
                    $periodLabel = "Triwulan II (April - Juni) $year";
                    break;
                case 3:
                    $startDate = Carbon::createFromDate($year, 7, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 9, 30)->endOfDay();
                    $periodLabel = "Triwulan III (Juli - September) $year";
                    break;
                case 4:
                    $startDate = Carbon::createFromDate($year, 10, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
                    $periodLabel = "Triwulan IV (Oktober - Desember) $year";
                    break;
            }
        } elseif ($filterType === 'semester') {
            if ($semester == 1) {
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
                $endDate = Carbon::createFromDate($year, 6, 30)->endOfDay();
                $periodLabel = "Semester I (Januari - Juni) $year";
            } else {
                $startDate = Carbon::createFromDate($year, 7, 1)->startOfDay();
                $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
                $periodLabel = "Semester II (Juli - Desember) $year";
            }
        } elseif ($filterType === 'year') {
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            $periodLabel = "Tahun $year";
        } elseif ($filterType === 'custom') {
            $startDate = Carbon::parse($request->get('date_from', Carbon::now()->startOfMonth()->toDateString()))->startOfDay();
            $endDate = Carbon::parse($request->get('date_to', Carbon::now()->endOfMonth()->toDateString()))->endOfDay();
            $periodLabel = $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y');
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        $expenses = $query->orderBy('tanggal', 'asc')->get();
        $totalAmount = $expenses->sum('nominal');
        $printedAt = Carbon::now()->translatedFormat('d F Y H:i');

        $pdf = Pdf::loadView('expenses.lpj_pdf', compact('school', 'expenses', 'totalAmount', 'periodLabel', 'printedAt'))
            ->setPaper('a4', 'portrait');

        $filename = 'Rekap_LPJ_BOSP_' . Str::slug($school->name) . '_' . Str::slug($periodLabel) . '.pdf';

        return $pdf->download($filename);
    }
}
