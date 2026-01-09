<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Zirconia Crown',
                'description' => 'High-quality zirconia crown with natural aesthetics and superior strength',
                'base_price' => 150.00,
                'turnaround_days' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Full Denture',
                'description' => 'Complete upper or lower denture with premium acrylic teeth',
                'base_price' => 450.00,
                'turnaround_days' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Partial Denture',
                'description' => 'Removable partial denture with metal framework',
                'base_price' => 320.00,
                'turnaround_days' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Implant Crown',
                'description' => 'Custom crown for dental implant restoration',
                'base_price' => 180.00,
                'turnaround_days' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Porcelain Bridge',
                'description' => '3-unit porcelain fused to metal bridge',
                'base_price' => 550.00,
                'turnaround_days' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Ceramic Veneer',
                'description' => 'Ultra-thin ceramic veneer for aesthetic enhancement',
                'base_price' => 200.00,
                'turnaround_days' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
