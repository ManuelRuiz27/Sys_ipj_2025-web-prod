<?php

namespace Database\Seeders;

use App\Models\Municipio;
use App\Models\Seccion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        $base = config('catalogos.path', database_path('seeders/data'));

        $munPath = $base.DIRECTORY_SEPARATOR.'municipios.csv';
        $secPath = $base.DIRECTORY_SEPARATOR.'secciones.csv';

        $munInserted = $munUpdated = 0;
        if (file_exists($munPath)) {
            $rows = $this->readCsv($munPath);
            foreach ($rows as $row) {
                $clave = (int)($row['clave'] ?? 0);
                $nombre = trim((string)($row['nombre'] ?? ''));
                if ($clave <= 0 || $nombre === '') {
                    continue;
                }
                $existing = Municipio::where('clave', $clave)->first();
                if ($existing) {
                    if ($existing->nombre !== $nombre) {
                        $existing->update(['nombre' => $nombre]);
                        $munUpdated++;
                    }
                } else {
                    Municipio::create(['clave' => $clave, 'nombre' => $nombre]);
                    $munInserted++;
                }
            }
            $this->log("Municipios: +{$munInserted}, ~{$munUpdated}, total=".Municipio::count());
            if (Municipio::count() !== 59) {
                $this->log('Aviso: el total de municipios no es 59', 'warning');
            }
        } else {
            $this->log("Archivo no encontrado: {$munPath}", 'warning');
        }

        $secInserted = $secUpdated = $secSkipped = 0;
        if (file_exists($secPath)) {
            $rows = $this->readCsv($secPath);
            foreach ($rows as $row) {
                $seccional = trim((string)($row['seccional'] ?? ''));
                $dl = trim((string)($row['distrito_local'] ?? ''));
                $df = trim((string)($row['distrito_federal'] ?? ''));

                $munId = null;
                if (isset($row['municipio_id']) && $row['municipio_id'] !== '') {
                    $munId = (int)$row['municipio_id'];
                } elseif (isset($row['municipio_clave']) && $row['municipio_clave'] !== '') {
                    $clave = (int)$row['municipio_clave'];
                    $munId = Municipio::where('clave', $clave)->value('id');
                }

                if ($seccional === '' || empty($munId)) {
                    $secSkipped++;
                    continue;
                }

                $existing = Seccion::where('seccional', $seccional)->first();
                $payload = [
                    'municipio_id' => $munId,
                    'distrito_local' => $dl,
                    'distrito_federal' => $df,
                ];
                if ($existing) {
                    $existing->update($payload);
                    $secUpdated++;
                } else {
                    Seccion::create(array_merge(['seccional' => $seccional], $payload));
                    $secInserted++;
                }
            }
            $this->log("Secciones: +{$secInserted}, ~{$secUpdated}, -{$secSkipped}");
        } else {
            $this->log("Archivo no encontrado: {$secPath}", 'warning');
        }
    }

    private function readCsv(string $path): array
    {
        $contents = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($contents === false || count($contents) === 0) {
            return [];
        }
        $headerLine = array_shift($contents);
        $delimiter = $this->detectDelimiter($headerLine);
        $headers = str_getcsv($headerLine, $delimiter);
        $rows = [];
        foreach ($contents as $line) {
            $cols = str_getcsv($line, $delimiter);
            if (count($cols) !== count($headers)) {
                // intentar con otro delimitador
                $altDelim = $delimiter === ';' ? ',' : ';';
                $cols = str_getcsv($line, $altDelim);
                if (count($cols) !== count($headers)) {
                    continue;
                }
            }
            $rows[] = array_combine($headers, $cols);
        }
        return $rows;
    }

    private function detectDelimiter(string $line): string
    {
        $commas = substr_count($line, ',');
        $semis = substr_count($line, ';');
        return $semis > $commas ? ';' : ',';
    }

    private function log(string $message, string $level = 'info'): void
    {
        if (isset($this->command)) {
            $this->command->{$level === 'warning' ? 'warn' : 'info'}($message);
        }
        Log::{$level}('[CatalogosSeeder] '.$message);
    }
}

