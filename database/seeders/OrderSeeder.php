<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get users by role
        $clients = User::role('client')->get();
        $technicians = User::role('technician')->get();
        $services = Service::all();

        // Check if we have the necessary data
        if ($clients->isEmpty() || $services->isEmpty()) {
            $this->command->warn('Please seed users and services first!');
            return;
        }

        $statuses = ['submitted', 'under_review', 'in_production', 'quality_check', 'ready_for_delivery', 'completed'];

        $orders = [
            [
                'order_number' => 'ORD-' . date('Y') . '-001',
                'client_id' => $clients->first()->id,
                'service_id' => $services->where('name', 'Zirconia Crown')->first()->id,
                'technician_id' => $technicians->isNotEmpty() ? $technicians->random()->id : null,
                'status' => 'in_production',
                'special_instructions' => 'Shade A2, margin chamfer',
                'due_date' => now()->addDays(5),
            ],
            [
                'order_number' => 'ORD-' . date('Y') . '-002',
                'client_id' => $clients->first()->id,
                'service_id' => $services->where('name', 'Full Denture')->first()->id,
                'technician_id' => $technicians->isNotEmpty() ? $technicians->random()->id : null,
                'status' => 'quality_check',
                'special_instructions' => 'Upper denture, medium tooth mold',
                'due_date' => now()->addDays(7),
            ],
            [
                'order_number' => 'ORD-' . date('Y') . '-003',
                'client_id' => $clients->count() > 1 ? $clients->skip(1)->first()->id : $clients->first()->id,
                'service_id' => $services->where('name', 'Implant Crown')->first()->id,
                'technician_id' => null,
                'status' => 'submitted',
                'special_instructions' => 'Screw-retained, shade B1',
                'due_date' => now()->addDays(5),
            ],
            [
                'order_number' => 'ORD-' . date('Y') . '-004',
                'client_id' => $clients->first()->id,
                'service_id' => $services->where('name', 'Partial Denture')->first()->id,
                'technician_id' => $technicians->isNotEmpty() ? $technicians->random()->id : null,
                'status' => 'ready_for_delivery',
                'special_instructions' => 'Metal framework, replace teeth 14, 15, 16',
                'due_date' => now()->addDays(1),
            ],
            [
                'order_number' => 'ORD-' . date('Y') . '-005',
                'client_id' => $clients->count() > 1 ? $clients->skip(1)->first()->id : $clients->first()->id,
                'service_id' => $services->where('name', 'Ceramic Veneer')->first()->id,
                'technician_id' => $technicians->isNotEmpty() ? $technicians->random()->id : null,
                'status' => 'under_review',
                'special_instructions' => 'Tooth 11, minimal prep veneer',
                'due_date' => now()->addDays(4),
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }

        $this->command->info('Orders seeded successfully!');
    }
}
