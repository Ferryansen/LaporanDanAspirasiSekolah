<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aspiration;
use App\Models\AspirationReaction;
use Illuminate\Support\Facades\Auth;

class AspirationReactionController extends Controller
{
    public function react(Request $request, Aspiration $aspiration)
    {
        $request->validate([
            'reaction' => 'required|in:like,dislike'
        ]);
    
        $user = Auth::user();
    
        // Check if the user has already reacted
        $existingReaction = AspirationReaction::where('user_id', $user->id)
                            ->where('aspiration_id', $aspiration->id)
                            ->first();
    
        if ($existingReaction) {
            // Update the reaction if it's different
            if ($existingReaction->reaction !== $request->reaction) {
                $existingReaction->update(['reaction' => $request->reaction]);
            } else {
                // Remove the reaction if it's the same
                $existingReaction->delete();
            }
        } else {
            // Create a new reaction
            AspirationReaction::create([
                'user_id' => $user->id,
                'aspiration_id' => $aspiration->id,
                'reaction' => $request->reaction,
            ]);
        }
    
        // Get updated reaction counts
        $likeCount = $aspiration->reactions()->where('reaction', 'like')->count();
        $dislikeCount = $aspiration->reactions()->where('reaction', 'dislike')->count();
    
        // Get the user's current reaction (if any)
        $userReaction = AspirationReaction::where('user_id', $user->id)
                            ->where('aspiration_id', $aspiration->id)
                            ->first();
    
        return response()->json([
            'success' => true,
            'like_count' => $likeCount,
            'dislike_count' => $dislikeCount,
            'user_reaction' => $userReaction ? $userReaction->reaction : null,
        ]);
    }
}