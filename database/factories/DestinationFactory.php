<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Destination;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mapCenterLatitude = config('destination.map_center_latitude');
        $mapCenterLongitude = config('destination.map_center_longitude');
        $minLatitude = $mapCenterLatitude - 0.05;
        $maxLatitude = $mapCenterLatitude + 0.05;
        $minLongitude = $mapCenterLongitude - 0.07;
        $maxLongitude = $mapCenterLongitude + 0.07;

        return [
            'name'       => ucwords($this->faker->words(2, true)),
            'address'    => $this->faker->address,
            'latitude'   => $this->faker->latitude($minLatitude, $maxLatitude),
            'longitude'  => $this->faker->longitude($minLongitude, $maxLongitude),
            'creator_id' => User::factory(),
        ];
    }
}
