<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        session(['comment_popup_open' => true, 'aspiration_id' => $aspiration->id]); // Set session data
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

        session(['comment_popup_open' => true, 'aspiration_id' => $comment->aspiration_id]); // Set session data
        return back();
    }

    public function clearSessionData()
    {
        Session::forget('comment_popup_open');
        Session::forget('aspiration_id');
        return redirect()->back();
    }
}

