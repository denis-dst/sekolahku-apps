<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\CompanyPage;
use Illuminate\Http\Request;

class PageManagementController extends Controller
{
    public function index()
    {
        $pages = CompanyPage::orderBy('id', 'asc')->get();
        return view('superadmin.pages.index', compact('pages'));
    }

    public function edit(CompanyPage $page)
    {
        return view('superadmin.pages.edit', compact('page'));
    }

    public function update(Request $request, CompanyPage $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string',
            'contact_maps_embed' => 'nullable|string',
        ]);

        $page->update([
            'title' => $request->title,
            'content' => $request->content,
            'meta_description' => $request->meta_description,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'contact_address' => $request->contact_address,
            'contact_maps_embed' => $request->contact_maps_embed,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman ' . $page->title . ' berhasil diperbarui!');
    }
}
