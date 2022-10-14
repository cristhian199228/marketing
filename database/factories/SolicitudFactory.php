<?php

namespace Database\Factories;

use App\Models\Proyecto;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class SolicitudFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Solicitud::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_solicitante' => User::where('is_admin', '<>', 1)->inRandomOrder()->first()->id,
            //'id_encargado' => User::where('is_admin', 1)->inRandomOrder()->first()->id,
            //'id_prioridad' => DB::table('prioridades')->inRandomOrder()->first()->id,
            'id_proyecto' => Proyecto::inRandomOrder()->first()->id,
            'objetivo' => $this->faker->word(),
            'descripcion' => $this->faker->text(),
            'contenido' => $this->faker->text(),
            'altura' => $this->faker->randomNumber(3),
            'ancho' => $this->faker->randomNumber(3),
            'unidad_medida' => $this->faker->randomElement(['m', 'cm', 'px']),
            'formato' => $this->faker->randomElement(['digital', 'impreso', 'video']),
            //'fecha_entrega' => $this->faker->date('Y-m-d'),
        ];
    }
}
