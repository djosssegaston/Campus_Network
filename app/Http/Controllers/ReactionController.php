<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Reaction;
use Illuminate\Http\RedirectResponse;

class ReactionController extends Controller
{
    /**
     * Store a new reaction (like) on a publication.
     */
    public function store(Publication $publication): RedirectResponse
    {
        // Check if user already reacted
        $existing = $publication->reactions()
            ->where('utilisateur_id', auth()->id())
            ->first();

        if ($existing) {
            // Toggle: if already liked, remove it
            $existing->delete();
        } else {
            // Create like
            $publication->reactions()->create([
                'utilisateur_id' => auth()->id(),
                'type' => 'like',
            ]);
        }

        return redirect()->back();
    }

    /**
     * Delete a reaction.
     */
    public function destroy(Reaction $reaction): RedirectResponse
    {
        // Authorization check
        if ($reaction->utilisateur_id !== auth()->id() && !auth()->user()->estAdmin()) {
            return redirect()->back()->with('error', 'Non autorisé');
        }

        $reaction->delete();

        return redirect()->back();
    }
}
