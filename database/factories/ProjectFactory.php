<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'path' => $this->faker->word(),
            'url' => $this->faker->url(),
            'php_version' => $this->faker->word(),
            'node_version' => $this->faker->word(),
            'type' => $this->faker->word(),
            'is_secure' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
