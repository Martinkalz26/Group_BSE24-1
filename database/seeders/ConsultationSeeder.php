<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Consultations;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Consultations::create([
            'type' => 'Corporate law',
            'base_fee' => 100.00,
            'extra_fee_per_minute' => 2.00,
        ]);

        Consultations::create([
            'type' => 'Civil law',
            'base_fee' => 80.00,
            'extra_fee_per_minute' => 1.50,
        ]);

        Consultations::create([
            'type' => 'Criminal law',
            'base_fee' => 150.00,
            'extra_fee_per_minute' => 3.00,
        ]);

        Consultations::create([
            'type' => 'Cyber law',
            'base_fee' => 100.00,
            'extra_fee_per_minute' => 2.00,
        ]);

        Consultations::create([
            'type' => 'Contract law',
            'base_fee' => 80.00,
            'extra_fee_per_minute' => 1.50,
        ]);

        Consultations::create([
            'type' => 'Immigration law',
            'base_fee' => 150.00,
            'extra_fee_per_minute' => 3.00,
        ]);

        Consultations::create([
            'type' => 'Mediation law',
            'base_fee' => 150.00,
            'extra_fee_per_minute' => 3.00,
        ]);
    }
}
