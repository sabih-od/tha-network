<?php

namespace Database\Seeders;

use App\Models\Goal;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Goal::truncate();
        $grades = ['Beginner', 'Amateur', 'Expert', 'THA King'];
        $targets = [100, 500, 1000, 10000];

        foreach ($grades as $key => $grade) {
            Goal::create([
                'name' => $grade,
                'target' => $targets[$key],
            ]);
        }
    }
}
