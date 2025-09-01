<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Database\Seeders\CatalogosSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CatalogosController extends Controller
{
    public function index()
    {
        return view('admin.catalogos.index');
    }

    public function import(Request $request)
    {
        $data = $request->validate([
            'municipios' => ['nullable','file','mimes:csv,txt'],
            'secciones' => ['nullable','file','mimes:csv,txt'],
            'sql' => ['nullable','file','mimes:sql,txt'],
            'fresh' => ['sometimes','boolean'],
        ]);

        if (! $request->hasFile('municipios') && ! $request->hasFile('secciones') && ! $request->hasFile('sql')) {
            return back()->withErrors(['municipios' => 'Sube al menos un archivo (CSV o SQL)'])->withInput();
        }

        // Preparar directorio temporal
        $dir = 'catalogos_import/'.now()->format('Ymd_His');
        Storage::makeDirectory($dir);
        $absDir = storage_path('app/'.$dir);

        if ($request->hasFile('municipios')) {
            $request->file('municipios')->storeAs($dir, 'municipios.csv');
        }
        if ($request->hasFile('secciones')) {
            $request->file('secciones')->storeAs($dir, 'secciones.csv');
        }

        if ($request->hasFile('sql')) {
            $sqlPath = $request->file('sql')->storeAs($dir, 'import.sql');
            $sqlFull = storage_path('app/'.$sqlPath);
            $sqlContents = @file_get_contents($sqlFull);
            if ($sqlContents === false) {
                return back()->withErrors(['sql' => 'No se pudo leer el archivo SQL']);
            }
            DB::unprepared($sqlContents);
        }

        if ($request->boolean('fresh')) {
            DB::table('secciones')->truncate();
            DB::table('municipios')->truncate();
        }

        // Ejecutar seeder apuntando al directorio con CSVs
        config(['catalogos.path' => $absDir]);
        Artisan::call('db:seed', [
            '--class' => CatalogosSeeder::class,
            '--force' => true,
        ]);

        $output = Artisan::output();
        return back()->with('status', 'Importación ejecutada')->with('import_log', $output);
    }
}

