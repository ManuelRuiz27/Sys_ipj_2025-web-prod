<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use App\Models\Beneficiario;
use App\Models\Municipio;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class BeneficiariosController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Beneficiario::class);

        $filters = $request->only(['municipio_id','seccional','capturista','from','to','estado']);
        $q = Beneficiario::with(['municipio','creador'])
            ->when($filters['municipio_id'] ?? null, fn($b,$v)=>$b->where('municipio_id',$v))
            ->when($filters['seccional'] ?? null, fn($b,$v)=>$b->where('seccional','like',"%$v%"))
            ->when($filters['capturista'] ?? null, fn($b,$v)=>$b->where('created_by',$v))
            ->when($filters['estado'] ?? null, function ($b,$v) {
                if ($v === 'borrador') return $b->where('is_draft', true);
                if ($v === 'registrado') return $b->where('is_draft', false);
            })
            ->when($filters['from'] ?? null, fn($b,$v)=>$b->whereDate('created_at','>=',$v))
            ->when($filters['to'] ?? null, fn($b,$v)=>$b->whereDate('created_at','<=',$v))
            ->orderByDesc('created_at');

        $ids = $request->user()->municipiosAsignados()->pluck('municipios.id');
        if ($ids->count() > 0) {
            $q->whereIn('municipio_id', $ids);
        }
        $beneficiarios = $q->paginate(15)->withQueryString();
        $municipios = Municipio::orderBy('nombre')->pluck('nombre','id');
        $capturistas = User::role('capturista')->orderBy('name')->get(['uuid','name']);

        return view('encargado.beneficiarios.index', compact('beneficiarios','filters','municipios','capturistas'));
    }

    public function show(Beneficiario $beneficiario)
    {
        $this->authorize('view', $beneficiario);
        $activities = Activity::forSubject($beneficiario)->latest()->limit(10)->get();
        return view('encargado.beneficiarios.show', compact('beneficiario','activities'));
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', Beneficiario::class);
        $filters = $request->only(['municipio_id','seccional','capturista','from','to','estado']);
        $q = Beneficiario::with(['municipio','creador'])
            ->when($filters['municipio_id'] ?? null, fn($b,$v)=>$b->where('municipio_id',$v))
            ->when($filters['seccional'] ?? null, fn($b,$v)=>$b->where('seccional','like',"%$v%"))
            ->when($filters['capturista'] ?? null, fn($b,$v)=>$b->where('created_by',$v))
            ->when($filters['estado'] ?? null, function ($b,$v) {
                if ($v === 'borrador') return $b->where('is_draft', true);
                if ($v === 'registrado') return $b->where('is_draft', false);
            })
            ->when($filters['from'] ?? null, fn($b,$v)=>$b->whereDate('created_at','>=',$v))
            ->when($filters['to'] ?? null, fn($b,$v)=>$b->whereDate('created_at','<=',$v))
            ->orderByDesc('created_at');

        $ids = $request->user()->municipiosAsignados()->pluck('municipios.id');
        if ($ids->count() > 0) {
            $q->whereIn('municipio_id', $ids);
        }

        $q = $q->get();

        

        $headers = ['Folio','Nombre','CURP','Municipio','Seccional','Capturista','Estado','Fecha'];
        $callback = function () use ($q, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($q as $b) {
                fputcsv($out, [
                    $b->folio_tarjeta,
                    trim($b->nombre.' '.$b->apellido_paterno.' '.$b->apellido_materno),
                    $b->curp,
                    optional($b->municipio)->nombre,
                    $b->seccional,
                    optional($b->creador)->name,
                    $b->is_draft ? 'Borrador' : 'Registrado',
                    optional($b->created_at)->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        };

        $filename = 'beneficiarios_encargado_'.now()->format('Ymd_His').'.csv';
        return response()->streamDownload($callback, $filename, ['Content-Type' => 'text/csv']);
    }
}
