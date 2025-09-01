<?php

namespace App\Http\Controllers;

use App\Models\Beneficiario;
use App\Models\Domicilio;
use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBeneficiarioRequest;
use App\Http\Requests\UpdateBeneficiarioRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BeneficiarioController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Beneficiario::class);
        $q = $request->get('q');
        $filters = $request->only([
            'municipio_id','seccional','distrito_local','distrito_federal','sexo','discapacidad','edad_min','edad_max'
        ]);

        $beneficiarios = Beneficiario::with(['municipio','creador'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('folio_tarjeta', 'like', "%$q%")
                        ->orWhere('curp', 'like', "%$q%")
                        ->orWhere('nombre', 'like', "%$q%")
                        ->orWhere('apellido_paterno', 'like', "%$q%")
                        ->orWhere('apellido_materno', 'like', "%$q%");
                });
            })
            ->when($filters['municipio_id'] ?? null, fn($q2,$v)=>$q2->where('municipio_id',$v))
            ->when($filters['seccional'] ?? null, fn($q2,$v)=>$q2->where('seccional','like',"%$v%"))
            ->when($filters['distrito_local'] ?? null, fn($q2,$v)=>$q2->where('distrito_local','like',"%$v%"))
            ->when($filters['distrito_federal'] ?? null, fn($q2,$v)=>$q2->where('distrito_federal','like',"%$v%"))
            ->when(($filters['sexo'] ?? '') !== '', fn($q2,$v)=>$q2->where('sexo',$v))
            ->when(($filters['discapacidad'] ?? '') !== '', fn($q2,$v)=>$q2->where('discapacidad',(bool)$v))
            ->when($filters['edad_min'] ?? null, fn($q2,$v)=>$q2->where('edad','>=',(int)$v))
            ->when($filters['edad_max'] ?? null, fn($q2,$v)=>$q2->where('edad','<=',(int)$v))
            ->when(auth()->user()?->hasRole('capturista'), function ($q2) {
                $q2->where('created_by', auth()->user()->uuid);
            })
            ->orderBy('created_at','desc')
            ->paginate(15)
            ->withQueryString();

        $municipios = Municipio::orderBy('nombre')->pluck('nombre','id');

        return view('beneficiarios.index', [
            'beneficiarios' => $beneficiarios,
            'q' => $q,
            'filters' => $filters,
            'municipios' => $municipios,
        ]);
    }

    public function create()
    {
        $municipios = Municipio::orderBy('nombre')->pluck('nombre','id');
        return view('beneficiarios.create', compact('municipios'));
    }

    public function store(StoreBeneficiarioRequest $request)
    {
        $data = $request->validated();

        $beneficiario = new Beneficiario($data);
        $beneficiario->id = (string) Str::uuid();
        $beneficiario->created_by = Auth::user()->uuid;
        $beneficiario->save();

        $this->saveDomicilio($request, $beneficiario);

        return redirect()->route('beneficiarios.index')->with('status', 'Beneficiario creado correctamente');
    }

    public function edit(Beneficiario $beneficiario)
    {
        $this->authorize('view', $beneficiario);
        $municipios = Municipio::orderBy('nombre')->pluck('nombre','id');
        $domicilio = $beneficiario->domicilio;
        return view('beneficiarios.edit', compact('beneficiario','municipios','domicilio'));
    }

    public function update(UpdateBeneficiarioRequest $request, Beneficiario $beneficiario)
    {
        $this->authorize('update', $beneficiario);
        $data = $request->validated();
        $beneficiario->fill($data);
        $beneficiario->save();

        $this->saveDomicilio($request, $beneficiario);

        return redirect()->route('beneficiarios.index')->with('status', 'Beneficiario actualizado correctamente');
    }

    public function destroy(Beneficiario $beneficiario)
    {
        $this->authorize('delete', $beneficiario);
        $beneficiario->delete();
        return redirect()->route('beneficiarios.index')->with('status', 'Beneficiario eliminado');
    }

    protected function saveDomicilio(Request $request, Beneficiario $beneficiario): void
    {
        $dom = $request->input('domicilio');
        if (!$dom) {
            return;
        }
        $payload = array_filter([
            'calle' => $dom['calle'] ?? null,
            'numero_ext' => $dom['numero_ext'] ?? null,
            'numero_int' => $dom['numero_int'] ?? null,
            'colonia' => $dom['colonia'] ?? null,
            'municipio' => $dom['municipio'] ?? null,
            'codigo_postal' => $dom['codigo_postal'] ?? null,
            'seccional' => $dom['seccional'] ?? null,
        ], fn ($v) => !is_null($v));

        if (empty($payload)) {
            return;
        }

        $domicilio = $beneficiario->domicilio ?: new Domicilio(['id' => (string) Str::uuid(), 'beneficiario_id' => $beneficiario->id]);
        $domicilio->fill($payload);
        $domicilio->beneficiario_id = $beneficiario->id;
        $domicilio->save();
    }
}
