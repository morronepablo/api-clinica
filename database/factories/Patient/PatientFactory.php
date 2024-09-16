<?php

namespace Database\Factories\Patient;

use App\Models\Patient\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->firstName($gender = 'male' | 'female'),  // Nombres argentinos
            "surname" => $this->faker->lastName(),  // Apellidos comunes en Argentina
            "mobile" => $this->faker->unique()->numerify('+54 9 ## ########'),  // Formato argentino de celular
            "email" => $this->faker->unique()->safeEmail(),
            "birth_date" => $this->faker->dateTimeBetween("1985-10-01", "2000-10-25"),
            "gender" => $this->faker->randomElement([1, 2]),  // 1 = Masculino, 2 = Femenino
            "education" => $this->faker->randomElement(['Primario', 'Secundario', 'Terciario', 'Universitario']),  // Educación en Argentina
            "address" => $this->faker->streetAddress() . ', ' . $this->faker->city() . ', ' . $this->faker->state(),  // Dirección argentina
            "antecedent_family" => $this->faker->text(300),
            "antecedent_personal" => $this->faker->text(200),
            "antecedent_allergic" => $this->faker->text(150),
            "current_disease" => $this->faker->text(100),
            "n_document" => $this->faker->unique()->numerify('########'),  // Formato típico de DNI
            "created_at" => $this->faker->dateTimeBetween("2024-01-01 00:00:00", "2024-12-25 23:59:59"),
        ];
    }
}
