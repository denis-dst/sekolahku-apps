<?php

namespace App\Http\Controllers;

use App\Models\CompanyPage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $page = CompanyPage::where('slug', 'tentang-kami')->firstOrFail();
        return view('pages.about', compact('page'));
    }

    public function contact()
    {
        $page = CompanyPage::where('slug', 'hubungi-kami')->firstOrFail();
        return view('pages.contact', compact('page'));
    }
}
