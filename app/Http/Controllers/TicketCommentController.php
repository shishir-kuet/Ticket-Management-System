<?php

namespace App\Http\Controllers;

use App\Models\TicketComment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        // Check if user can access this ticket
        $user = Auth::user();
        if ($user->role === 'customer' && $ticket->customer_id !== $user->id) {
            abort(403, 'You can only comment on your own tickets.');
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Comment added successfully!');
    }

    public function update(Request $request, TicketComment $comment)
    {
        // Only the comment author can edit their comment
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own comments.');
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment->update([
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Comment updated successfully!');
    }

    public function destroy(TicketComment $comment)
    {
        // Only the comment author or admin can delete
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'You can only delete your own comments.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
