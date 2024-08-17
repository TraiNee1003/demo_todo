<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['pending', 'processing', 'completed', 'rejected'];

        foreach ($statuses as $status) {
            Status::create(['name' => $status]);
        }
    }
}
