<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="h4 m-0">Beneficiarios</h2>
            <a href="{{ route('beneficiarios.create') }}" class="btn btn-primary">Nuevo</a>
        </div>
    </x-slot>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form class="row gy-2 gx-3 align-items-end" method="GET">
                <div class="col-12 col-md-3">
                    <label class="form-label">Búsqueda</label>
                    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Folio, CURP o nombre">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Municipio</label>
                    <select name="municipio_id" class="form-select">
                        <option value="">—</option>
                        @foreach($municipios as $id=>$nombre)
                            <option value="{{ $id }}" @selected(($filters['municipio_id'] ?? '')==$id)>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Seccional</label>
                    <input name="seccional" value="{{ $filters['seccional'] ?? '' }}" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Distrito local</label>
                    <input name="distrito_local" value="{{ $filters['distrito_local'] ?? '' }}" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Distrito federal</label>
                    <input name="distrito_federal" value="{{ $filters['distrito_federal'] ?? '' }}" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Sexo</label>
                    <select name="sexo" class="form-select">
                        <option value="">—</option>
                        @foreach(['M'=>'M','F'=>'F','X'=>'X'] as $key=>$val)
                            <option value="{{ $key }}" @selected(($filters['sexo'] ?? '')===$key)>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Discapacidad</label>
                    <select name="discapacidad" class="form-select">
                        <option value="">—</option>
                        <option value="1" @selected(($filters['discapacidad'] ?? '')==='1')>Sí</option>
                        <option value="0" @selected(($filters['discapacidad'] ?? '')==='0')>No</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Borrador</label>
                    <select name="is_draft" class="form-select">
                        <option value="">—</option>
                        <option value="1" @selected(($filters['is_draft'] ?? '')==='1')>Sí</option>
                        <option value="0" @selected(($filters['is_draft'] ?? '')==='0')>No</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Edad mín</label>
                    <input type="number" name="edad_min" value="{{ $filters['edad_min'] ?? '' }}" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Edad máx</label>
                    <input type="number" name="edad_max" value="{{ $filters['edad_max'] ?? '' }}" class="form-control">
                </div>
                <div class="col-12 col-md-3 ms-auto text-end">
                    <a href="{{ route('beneficiarios.index') }}" class="btn btn-outline-secondary me-2"><i class="bi bi-arrow-counterclockwise me-1"></i>Limpiar</a>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Folio</th>
                        <th>Nombre</th>
                        <th>CURP</th>
                        <th>Municipio</th>
                        <th>Seccional</th>
                        <th>Capturista</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($beneficiarios as $b)
                    <tr>
                        <td>{{ $b->folio_tarjeta }}</td>
                        <td>{{ $b->nombre }} {{ $b->apellido_paterno }} {{ $b->apellido_materno }}</td>
                        <td>{{ $b->curp }}</td>
                        <td>{{ optional($b->municipio)->nombre }}</td>
                        <td>{{ $b->seccional }}</td>
                        <td>{{ optional($b->creador)->name }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('beneficiarios.edit', $b) }}"><i class="bi bi-pencil-square me-1"></i>Editar</a>
                            <form action="{{ route('beneficiarios.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar beneficiario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Sin registros</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($beneficiarios->hasPages())
            <div class="card-footer">{{ $beneficiarios->links() }}</div>
        @endif
    </div>
</x-app-layout>
