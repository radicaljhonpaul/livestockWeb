<?php

namespace Database\Factories;

use App\Models\Farmer;
use App\Models\AdminLevelThree;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class FarmerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Farmer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'pcc_system_id' => $this->faker->uuid(),
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'gender' => 'f',
            'birthdate' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'mobile_number' => $this->faker->phoneNumber(),
            'phone_number' => $this->faker->phoneNumber(),
            'fb_profile' => $this->faker->url(),
            'lat' => $this->faker->latitude($min = -90, $max = 90),
            'longitude' => $this->faker->longitude($min = -180, $max = 180),
            'admin_level_three_id' => AdminLevelThree::factory(),
            
        ];
    }
}
