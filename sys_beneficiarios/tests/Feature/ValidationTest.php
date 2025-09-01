<?php

namespace Tests\Feature;

use App\Models\Municipio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    protected function validPayload(array $overrides = []): array
    {
        $mun = Municipio::firstOrCreate(['clave'=>1],['nombre'=>'Test']);
        return array_merge([
            'folio_tarjeta' => 'FT-'.rand(100,999),
            'nombre' => 'Juan',
            'apellido_paterno' => 'Perez',
            'apellido_materno' => 'Lopez',
            'curp' => 'PEPJ000101HDFLRNA1',
            'fecha_nacimiento' => '2000-01-01',
            'sexo' => 'M',
            'discapacidad' => '0',
            'id_ine' => 'INE123',
            'telefono' => '5512345678',
            'municipio_id' => $mun->id,
            'seccional' => '001',
            'distrito_local' => 'DL',
            'distrito_federal' => 'DF',
            'is_draft' => '1',
            'domicilio' => [
                'calle' => 'Calle', 'numero_ext'=>'1', 'colonia'=>'Centro', 'municipio'=>'X', 'codigo_postal'=>'01234', 'seccional'=>'001'
            ],
        ], $overrides);
    }

    public function test_invalid_curp_rejected(): void
    {
        $u = User::factory()->create(); $u->assignRole('capturista');
        $payload = $this->validPayload(['curp' => 'INVALIDA0000000000']);
        $this->actingAs($u)->post(route('beneficiarios.store'), $payload)->assertSessionHasErrors('curp');
    }

    public function test_invalid_phone_rejected(): void
    {
        $u = User::factory()->create(); $u->assignRole('capturista');
        $payload = $this->validPayload(['telefono' => '123']);
        $this->actingAs($u)->post(route('beneficiarios.store'), $payload)->assertSessionHasErrors('telefono');
    }

    public function test_unique_folio(): void
    {
        $u = User::factory()->create(); $u->assignRole('capturista');
        $p1 = $this->validPayload(['folio_tarjeta' => 'FT-1']);
        $p2 = $this->validPayload(['folio_tarjeta' => 'FT-1']);
        $this->actingAs($u)->post(route('beneficiarios.store'), $p1);
        $this->actingAs($u)->post(route('beneficiarios.store'), $p2)->assertSessionHasErrors('folio_tarjeta');
    }
}
