<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectAndTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();

        if ($users->count() !== 2) {
            throw new \Exception('There should be exactly two normal users for this seeder.');
        }

        Project::factory()->count(10)->create()->each(function ($project) use ($users) {
            $userIndex = 0;

            // Create 4 tasks for each project
            for ($i = 0; $i < 10; $i++) {
                Task::factory()->create([
                    'project_id' => $project->id,
                    'user_id' => $users[$userIndex]->id,
                ]);
                $userIndex = 1 - $userIndex;
            }
        });
    }
}
