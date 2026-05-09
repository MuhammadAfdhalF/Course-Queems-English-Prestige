<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::query()
            ->where('is_active', true)
            ->with([
                'socialLinks' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('platform');
                },
            ])
            ->latest()
            ->first();

        $socialLinks = $contact?->socialLinks ?? collect();

        return view('pages.public.contact', compact(
            'contact',
            'socialLinks'
        ));
    }
}
