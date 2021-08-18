<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'abbreviate' => $this->faker->text(10),
            'address' => $this->faker->address,
            'phone' => $this->faker->numerify('###########'),
            'fax' => $this->faker->numerify('###########'),
        ];
    }
}
