<?php

namespace Database\Seeders;

use App\Models\Patient\Patient;
use Illuminate\Database\Seeder;
use App\Models\Patient\PatientPerson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::factory()->count(100)->create()->each(function ($p) {
            $faker = \Faker\Factory::create();
            PatientPerson::create([
                "patient_id" => $p->id,
                "name_companion" => $faker->firstName($gender = 'male' | 'female'),  // Nombres argentinos
                "surname_companion" => $faker->lastName(),
                "mobile_companion" => $faker->unique()->numerify('+54 9 ## ########'),  // Formato argentino de celular
                "relationship_companion" => $faker->randomElement(["Tio", "Mama", "Papa", "Hermano"]),
                "name_responsible" => $faker->name(),
                "surname_responsible" => $faker->lastName(),
                "mobile_responsible" => $faker->unique()->numerify('+54 9 ## ########'),  // Formato argentino de celular
                "relationship_responsible" => $faker->randomElement(["Tio", "Mama", "Papa", "Hermano"]),
            ]);
        });;
        // php artisan db:seed --class=PatientSeeder
    }
}
