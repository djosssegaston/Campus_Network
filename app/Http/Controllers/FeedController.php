<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\View\View;

class FeedController extends Controller
{
    /**
     * Display the feed with publications.
     */
    public function index(): View
    {
        $publications = Publication::with(['utilisateur', 'commentaires', 'reactions'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('feed', [
            'publications' => $publications
        ]);
    }
}
