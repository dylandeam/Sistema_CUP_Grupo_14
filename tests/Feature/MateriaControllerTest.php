<?php

namespace Tests\Feature;

use App\Models\Materia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MateriaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_guarda_una_materia_con_ponderacion_decimal(): void
    {
        $this->withoutMiddleware();

        $response = $this->post('/admin/materias', [
            'nombre' => 'Computación',
            'ponderacion' => '0.25',
        ]);

        $response->assertRedirect('/admin/materias');

        $this->assertDatabaseHas('materias', [
            'nombre' => 'Computación',
            'ponderacion' => '0.25',
        ]);
    }
}
