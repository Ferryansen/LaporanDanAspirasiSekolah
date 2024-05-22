<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Aspiration $aspiration)
    {
        $request->validate([
            'body' => 'required',
        ]);

        $aspiration->comments()->create([
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return back();
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'body' => 'required',
        ]);

        $comment->replies()->create([
            'body' => $request->body,
            'user_id' => auth()->id(),
            'aspiration_id' => $comment->aspiration_id,
        ]);

        return back();
    }
}

