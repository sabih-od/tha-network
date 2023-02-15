<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CmsController extends Controller
{
    public function about (Request $request)
    {
        $about = Page::where('name', 'About')->first();
        $data = json_decode($about->content ?? []);

        return Inertia::render('About', [
            'data' => $data
        ]);
    }

    public function home (Request $request)
    {
        $home = Page::where('name', 'Home')->first();
        $data = json_decode($home->content ?? []);

        return Inertia::render('HowItWorks', [
            'data' => $data
        ]);
    }

    public function benefits (Request $request)
    {
        $benefits = Page::where('name', 'Benefits')->first();
        $data = json_decode($benefits->content ?? []);

        return Inertia::render('Benifits', [
            'data' => $data
        ]);
    }
}
