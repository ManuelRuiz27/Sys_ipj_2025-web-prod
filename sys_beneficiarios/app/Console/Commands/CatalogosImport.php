<?php

namespace App\Console\Commands;

use Database\Seeders\CatalogosSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CatalogosImport extends Command
{
    protected $signature = 'catalogos:import {--path= : Directorio base de CSVs (municipios.csv, secciones.csv)} {--sql= : Ruta de archivo SQL a ejecutar} {--fresh : Limpiar tablas antes de importar}';

    protected $description = 'Importa catálogos de municipios y secciones desde CSV/SQL con logs de progreso';

    public function handle(): int
    {
        $path = $this->option('path') ?: database_path('seeders/data');
        $sql = $this->option('sql');
        $fresh = (bool)$this->option('fresh');

        $this->info('Iniciando importación de catálogos');
        $this->line('Ruta CSV: '.$path);

        if ($fresh) {
            $this->warn('Fresh: limpiando tablas municipios y secciones...');
            DB::table('secciones')->truncate();
            DB::table('municipios')->truncate();
        }

        if ($sql) {
            $this->info('Ejecutando SQL: '.$sql);
            $sqlContents = @file_get_contents($sql);
            if ($sqlContents === false) {
                $this->error('No se pudo leer el archivo SQL');
                return self::FAILURE;
            }
            DB::unprepared($sqlContents);
        }

        // Establecer path para el seeder
        config(['catalogos.path' => $path]);

        $this->info('Ejecutando CatalogosSeeder...');
        Artisan::call('db:seed', [
            '--class' => CatalogosSeeder::class,
            '--force' => true,
        ]);
        $this->line(Artisan::output());

        $this->info('Importación finalizada');
        return self::SUCCESS;
    }
}

