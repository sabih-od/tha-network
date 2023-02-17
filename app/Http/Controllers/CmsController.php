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

    public function terms (Request $request)
    {
        $benefits = Page::where('name', 'Terms')->first();
        $data = json_decode($benefits->content ?? []);

        return Inertia::render('Terms', [
            'data' => $data
        ]);
    }

    public function privacy (Request $request)
    {
        $privacy = Page::where('name', 'Privacy')->first();
        $data = json_decode($privacy->content ?? []);

        return Inertia::render('Privacy', [
            'data' => $data
        ]);
    }

    public function contact (Request $request)
    {
        $contact = Page::where('name', 'Contact')->first();
        $data = json_decode($contact->content ?? []);

        return Inertia::render('Contact', [
            'data' => $data
        ]);
    }
}
