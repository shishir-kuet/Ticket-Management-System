<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $tickets = Ticket::with(['customer', 'category', 'agent'])
                ->latest()
                ->paginate(15);
        } elseif ($user->role === 'agent') {
            $tickets = Ticket::with(['customer', 'category', 'agent'])
                ->where(function($query) use ($user) {
                    $query->where('agent_id', $user->id)
                          ->orWhereNull('agent_id');
                })
                ->latest()
                ->paginate(15);
        } else {
            $tickets = Ticket::with(['category', 'agent'])
                ->where('customer_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = TicketCategory::all();
        $customers = [];
        
        // If agent/admin, allow creating tickets for customers
        if (in_array(Auth::user()->role, ['agent', 'admin'])) {
            $customers = User::where('role', 'customer')
                ->orderBy('name')
                ->get();
        }
        
        return view('tickets.create', compact('categories', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category_id' => 'required|exists:ticket_categories,id',
            'customer_id' => 'sometimes|exists:users,id', // Only for agents/admins
        ]);

        // Determine customer_id based on user role
        $customerId = Auth::id(); // Default: current user
        
        // If agent/admin and customer_id provided, use that customer
        if (in_array(Auth::user()->role, ['agent', 'admin']) && $request->filled('customer_id')) {
            $customerId = $request->customer_id;
        }

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open',
            'customer_id' => $customerId,
            'category_id' => $request->category_id,
        ]);

        $message = Auth::user()->role === 'customer' 
            ? 'Ticket created successfully!' 
            : 'Ticket created successfully for customer!';

        return redirect()->route('tickets.show', $ticket)
            ->with('success', $message);
    }

    public function show(Ticket $ticket)
    {
        $this->authorizeTicketAccess($ticket);
        
                $ticket->load(['customer', 'category', 'agent', 'comments.user']);
        
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $this->authorizeTicketEdit($ticket);
        
        $categories = TicketCategory::all();
        $agents = User::where('role', 'agent')->get();
        
        return view('tickets.edit', compact('ticket', 'categories', 'agents'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorizeTicketEdit($ticket);

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category_id' => 'required|exists:ticket_categories,id',
        ];

        // Only admin and agents can update status and assignment
        if (Auth::user()->role !== 'customer') {
            $rules['status'] = 'required|in:open,in_progress,closed';
            $rules['agent_id'] = 'nullable|exists:users,id';
        }

        $request->validate($rules);

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'category_id' => $request->category_id,
        ];

        if (Auth::user()->role !== 'customer') {
            $updateData['status'] = $request->status;
            $updateData['agent_id'] = $request->agent_id;
        }

        $ticket->update($updateData);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        // Only admin can delete tickets
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can delete tickets.');
        }

        $ticket->comments()->delete();
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        // Only admin and agents can assign tickets
        if (!in_array(Auth::user()->role, ['admin', 'agent'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'agent_id' => 'nullable|exists:users,id',
        ]);

        // If agent_id is null or empty, unassign the ticket
        if (empty($request->agent_id)) {
            $ticket->update([
                'agent_id' => null,
                'status' => 'open', // Reset to open when unassigned
            ]);

            return back()->with('success', 'Ticket unassigned successfully!');
        }

        $agent = User::findOrFail($request->agent_id);
        
        if ($agent->role !== 'agent') {
            return back()->with('error', 'Can only assign tickets to agents.');
        }

        $ticket->update([
            'agent_id' => $request->agent_id,
            'status' => 'in_progress',
        ]);

        return back()->with('success', 'Ticket assigned successfully!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        // Only admin and agents can update status
        if (!in_array(Auth::user()->role, ['admin', 'agent'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Ticket status updated successfully!');
    }

    private function authorizeTicketAccess(Ticket $ticket)
    {
        $user = Auth::user();
        
        if ($user->role === 'customer' && $ticket->customer_id !== $user->id) {
            abort(403, 'You can only view your own tickets.');
        }
    }

    private function authorizeTicketEdit(Ticket $ticket)
    {
        $user = Auth::user();
        
        if ($user->role === 'customer' && $ticket->customer_id !== $user->id) {
            abort(403, 'You can only edit your own tickets.');
        }
    }
}
