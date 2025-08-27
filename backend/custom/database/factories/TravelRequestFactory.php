<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\TravelRequest;

class TravelRequestFactory extends Factory
{
    protected $model = TravelRequest::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'destino' => $this->faker->city,
            'data_ida' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'data_volta' => $this->faker->dateTimeBetween('+3 weeks', '+1 month'),
            'status' => 'solicitado',
        ];
    }
}
