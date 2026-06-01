<?php

namespace Tests\Feature;

use App\Models\Gestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GestionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guarda_una_gestion_con_anio_sin_error(): void
    {
        $this->withoutMiddleware();

        $response = $this->post('/admin/gestiones', [
            'semestre' => '1',
            'anio' => '2026',
            'estado' => 'Activa',
        ]);

        $response->assertRedirect('/admin/gestiones');

        $this->assertDatabaseHas('gestions', [
            'semestre' => '1',
            'año' => 2026,
            'estado' => 'Activa',
        ]);
    }

    public function test_no_permite_repetir_semestre_y_anio(): void
    {
        $this->withoutMiddleware();

        Gestion::create([
            'semestre' => '1',
            'año' => 2026,
            'estado' => 'Cerrada',
        ]);

        $response = $this->from('/admin/gestiones/create')->post('/admin/gestiones', [
            'semestre' => '1',
            'anio' => '2026',
            'estado' => 'Activa',
        ]);

        $response->assertRedirect('/admin/gestiones/create');
        $response->assertSessionHasErrors(['año']);

        $errors = $response->getSession()->get('errors');
        $this->assertSame('The nombre has already been taken.', $errors->get('año')[0]);
    }
}
