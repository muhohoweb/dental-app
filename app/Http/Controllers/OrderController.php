<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Admin: View all orders
     */
    public function index(): Response
    {
        $orders = Order::with(['client', 'service', 'technician'])
            ->latest()
            ->paginate(15);

        $technicians = User::role('technician')->get();

        return Inertia::render('orders/admin/Index', [
            'orders' => $orders,
            'technicians' => $technicians,
        ]);
    }

    public function assignTechnician(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'technician_id' => $validated['technician_id'],
            'status' => 'under_review', // Auto-update status
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Technician assigned successfully.');
    }

    /**
     * Client: View own orders
     */
    public function clientOrders(): Response
    {
        $orders = Order::with(['service', 'technician'])
            ->where('client_id', auth()->id())
            ->latest()
            ->paginate(15);

        $services = Service::where('is_active', true)->get();

        return Inertia::render('orders/clients/Index', [
            'orders' => $orders,
            'services' => $services, // Add this
        ]);
    }

    /**
     * Show create order form (both admin and client can access)
     */
    public function create(): Response
    {
        $services = Service::where('is_active', true)->get();

        // Admin sees admin create form, client sees client form
        $view = auth()->user()->hasRole('admin')
            ? 'Orders/Admin/Create'
            : 'Orders/Client/Create';

        return Inertia::render($view, [
            'services' => $services,
        ]);
    }

    /**
     * Store new order
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        // Generate order number
        $lastOrder = Order::latest()->first();
        $orderCount = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;
        $orderNumber = 'ORD-' . date('Y') . '-' . str_pad($orderCount, 4, '0', STR_PAD_LEFT);

        // Get service to calculate due date
        $service = Service::findOrFail($validated['service_id']);

        $order = Order::create([
            'order_number' => $orderNumber,
            'client_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'special_instructions' => $validated['special_instructions'] ?? null,
            'due_date' => now()->addDays($service->turnaround_days),
            'status' => 'submitted',
        ]);

        // Redirect based on role
        $route = auth()->user()->hasRole('admin')
            ? 'orders.index'
            : 'orders.client.index';

        return redirect()->route($route)
            ->with('success', 'Order created successfully! Order #' . $orderNumber);
    }
}
