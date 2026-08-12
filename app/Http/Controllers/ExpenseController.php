<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ExpenseReceipt;
use App\Models\ExpenseStatusHistory;
use App\Models\Reimbursement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $categories = ExpenseCategory::where('school_id', $schoolId)->get();

        $query = Expense::where('school_id', $schoolId)->with(['user', 'category', 'receipts', 'statusHistories']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        $expenses = $query->latest()->paginate(15);

        $totalTalangan = Expense::where('school_id', $schoolId)->sum('nominal');
        $totalPending = Expense::where('school_id', $schoolId)->whereIn('status', ['Belum Diajukan', 'Diajukan'])->sum('nominal');
        $totalDibayar = Expense::where('school_id', $schoolId)->where('status', 'Dibayar')->sum('nominal');

        return view('expenses.index', compact('categories', 'expenses', 'totalTalangan', 'totalPending', 'totalDibayar'));
    }

    /**
     * Fast Entry Talangan (< 30 seconds)
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:1',
            'uraian' => 'required|string',
            'toko_vendor' => 'nullable|string',
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

        // Status History Log
        ExpenseStatusHistory::create([
            'expense_id' => $expense->id,
            'user_id' => $user->id,
            'status_sebelum' => null,
            'status_sesudah' => 'Belum Diajukan',
            'catatan' => 'Pencatatan pengeluaran talangan baru',
        ]);

        // Upload Receipts
        if ($request->hasFile('receipts')) {
            foreach ($request->file('receipts') as $file) {
                $path = $file->store('receipts', 'public');
                ExpenseReceipt::create([
                    'expense_id' => $expense->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Talangan pribadi berhasil dicatat!');
    }

    /**
     * Update Status / Approve / Reimburse Workflow
     */
    public function updateStatus(Request $request, Expense $expense)
    {
        $request->validate([
            'status' => 'required|in:Diajukan,Disetujui,Dibayar,Ditolak',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();
        $statusSebelum = $expense->status;
        $statusSesudah = $request->status;

        $expense->update(['status' => $statusSesudah]);

        ExpenseStatusHistory::create([
            'expense_id' => $expense->id,
            'user_id' => $user->id,
            'status_sebelum' => $statusSebelum,
            'status_sesudah' => $statusSesudah,
            'catatan' => $request->catatan ?: "Perubahan status ke {$statusSesudah}",
        ]);

        if ($statusSesudah === 'Dibayar') {
            Reimbursement::create([
                'school_id' => $expense->school_id,
                'expense_id' => $expense->id,
                'user_id' => $user->id,
                'nominal_reimburse' => $expense->nominal,
                'tanggal_pencairan' => now(),
                'metode_transfer' => 'Cash',
                'catatan' => 'Pencairan reimbursement talangan',
            ]);
        }

        return redirect()->back()->with('success', "Status pengeluaran berhasil diperbarui menjadi: {$statusSesudah}");
    }

    /**
     * Export LPJ BOSP Report to PDF
     */
    public function exportPdf(Request $request)
    {
        $school = Auth::user()->school;
        $expenses = Expense::where('school_id', $school->id)
            ->with(['user', 'category', 'receipts'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('expenses.lpj_pdf', compact('school', 'expenses'));
        return $pdf->download('LPJ_BOSP_' . date('Y-m-d') . '.pdf');
    }
}
