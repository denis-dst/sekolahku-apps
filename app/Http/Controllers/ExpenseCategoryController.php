<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        $categories = ExpenseCategory::withCount('expenses')
            ->where('school_id', $schoolId)
            ->latest()
            ->get();

        return view('expenses.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'kode_bosp' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string|max:500',
        ]);

        ExpenseCategory::create([
            'school_id' => $schoolId,
            'nama_kategori' => $request->nama_kategori,
            'kode_bosp' => $request->kode_bosp,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Kategori pengeluaran BOSP baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $schoolId = Auth::user()->school_id;
        $category = ExpenseCategory::where('school_id', $schoolId)->findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'kode_bosp' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'kode_bosp' => $request->kode_bosp,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Kategori pengeluaran BOSP berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $schoolId = Auth::user()->school_id;
        $category = ExpenseCategory::where('school_id', $schoolId)->findOrFail($id);

        if ($category->expenses()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori ini tidak dapat dihapus karena sudah memiliki data pengeluaran terikat.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Kategori pengeluaran BOSP berhasil dihapus.');
    }
}
