<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view.
     */
    public function index(): View
    {
        $user = Auth::user();
        $roleSlug = $user && $user->role ? $user->role->slug : null;
        
        return view('dashboard', [
            'roleSlug' => $roleSlug,
        ]);
    }
}
