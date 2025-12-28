<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Support\Facades\Auth;

trait AuthenticatedUser
{
    /**
     * Get current authenticated user for API.
     */
    protected function user()
    {
        return Auth::guard('sanctum')->user();
    }

    /**
     * Get current authenticated user ID for API.
     */
    protected function userId()
    {
        return Auth::guard('sanctum')->id();
    }
}
