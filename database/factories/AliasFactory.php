<?php

namespace Database\Factories;

use App\Models\Alias;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AliasFactory extends Factory
{
    protected $model = Alias::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'command' => $this->faker->word(),
            'description' => $this->faker->text(),
            'is_global' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
