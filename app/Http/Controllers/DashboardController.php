<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketCategory;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'agent':
                return $this->agentDashboard();
            case 'customer':
                return $this->customerDashboard();
            default:
                return $this->customerDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'in_progress_tickets' => Ticket::where('status', 'in_progress')->count(),
            'closed_tickets' => Ticket::where('status', 'closed')->count(),
            'unassigned_tickets' => Ticket::whereNull('agent_id')->where('status', '!=', 'closed')->count(),
            'total_users' => User::count(),
            'agents' => User::where('role', 'agent')->count(),
            'customers' => User::where('role', 'customer')->count(),
        ];

        $recent_tickets = Ticket::with(['customer', 'category', 'agent'])
            ->latest()
            ->take(10)
            ->get();

        $recent_users = User::latest()
            ->take(5)
            ->get();

        // Get unassigned tickets for assignment
        $unassigned_tickets = Ticket::with(['customer', 'category'])
            ->whereNull('agent_id')
            ->where('status', '!=', 'closed')
            ->latest()
            ->take(10)
            ->get();

        // Get available agents for assignment
        $available_agents = User::where('role', 'agent')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_tickets', 'recent_users', 'unassigned_tickets', 'available_agents'));
    }

    private function agentDashboard()
    {
        $agent = auth()->user();
        
        $stats = [
            'assigned_tickets' => Ticket::where('agent_id', $agent->id)->count(),
            'open_tickets' => Ticket::where('agent_id', $agent->id)->where('status', 'open')->count(),
            'in_progress_tickets' => Ticket::where('agent_id', $agent->id)->where('status', 'in_progress')->count(),
            'completed_today' => Ticket::where('agent_id', $agent->id)
                ->where('status', 'closed')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        $my_tickets = Ticket::with(['customer', 'category'])
            ->where('agent_id', $agent->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        $unassigned_tickets = Ticket::with(['customer', 'category'])
            ->whereNull('agent_id')
            ->where('status', 'open')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        return view('agent.dashboard', compact('stats', 'my_tickets', 'unassigned_tickets'));
    }

    private function customerDashboard()
    {
        $customer = auth()->user();
        
        $stats = [
            'total_tickets' => Ticket::where('customer_id', $customer->id)->count(),
            'open_tickets' => Ticket::where('customer_id', $customer->id)->where('status', 'open')->count(),
            'in_progress_tickets' => Ticket::where('customer_id', $customer->id)->where('status', 'in_progress')->count(),
            'closed_tickets' => Ticket::where('customer_id', $customer->id)->where('status', 'closed')->count(),
        ];

        $my_tickets = Ticket::with(['category', 'agent'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->take(10)
            ->get();

        $categories = TicketCategory::where('is_active', true)->get();

        return view('customer.dashboard', compact('stats', 'my_tickets', 'categories'));
    }
}
