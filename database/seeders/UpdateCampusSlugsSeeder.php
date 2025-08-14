<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateCampusSlugsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campuses = Campus::whereNull('slug')
            ->orWhere('slug', '')
            ->get();

        foreach ($campuses as $campus) {
            $slug = Str::slug($campus->name);

            $originalSlug = $slug;
            $counter = 1;
            while (Campus::where('slug', $slug)->where('id', '!=', $campus->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $campus->slug = $slug;
            $campus->save();

            $this->command->info("Updated campus ID {$campus->id} with slug: {$slug}");
        }

        $this->command->info("✅ Campus slug update complete.");
    }
}
