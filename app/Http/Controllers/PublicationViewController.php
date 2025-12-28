<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PublicationViewController extends Controller
{
    /**
     * Display the create publication form.
     */
    public function create(): View
    {
        return view('publications.create');
    }
}
