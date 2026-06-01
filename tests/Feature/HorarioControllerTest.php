<?php

namespace Tests\Feature;

use App\Models\Horario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HorarioControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guarda_un_horario_y_evita_duplicados(): void
    {
        $this->withoutMiddleware();

        $response = $this->post('/admin/horarios', [
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
        ]);

        $response->assertRedirect('/admin/horarios');

        $this->assertDatabaseHas('horarios', [
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
        ]);

        // intentar crear duplicado
        $response2 = $this->from('/admin/horarios/create')->post('/admin/horarios', [
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
        ]);

        $response2->assertRedirect('/admin/horarios/create');
        $response2->assertSessionHasErrors(['hora_fin']);

        $errors = $response2->getSession()->get('errors');
        $this->assertSame('Ya existe un horario con esa hora de inicio y fin.', $errors->get('hora_fin')[0]);
    }
}
