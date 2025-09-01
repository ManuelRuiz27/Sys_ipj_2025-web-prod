<x-app-layout>
    <x-slot name="header"><h2 class="h4 m-0">Beneficiarios (Encargado)</h2></x-slot>

    <div class="card mb-3">
        <div class="card-body">
            <form class="row gy-2 gx-3 align-items-end" method="GET">
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
                <div class="col-12 col-md-3">
                    <label class="form-label">Capturista</label>
                    <select name="capturista" class="form-select">
                        <option value="">—</option>
                        @foreach($capturistas as $u)
                            <option value="{{ $u->uuid }}" @selected(($filters['capturista'] ?? '')==$u->uuid)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">—</option>
                        <option value="borrador" @selected(($filters['estado'] ?? '')==='borrador')>Borrador</option>
                        <option value="registrado" @selected(($filters['estado'] ?? '')==='registrado')>Registrado</option>
                    </select>
                </div>
                <div class="col-12 col-md-4 ms-auto text-end">
                    <a href="{{ route('encargado.beneficiarios.index') }}" class="btn btn-outline-secondary me-2">Limpiar</a>
                    <a class="btn btn-outline-success me-2" href="{{ route('encargado.beneficiarios.export', request()->query()) }}">Exportar CSV</a>
                    <button class="btn btn-primary" type="submit">Filtrar</button>
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
                        <th>Municipio</th>
                        <th>Seccional</th>
                        <th>Capturista</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiarios as $b)
                        <tr>
                            <td>{{ $b->folio_tarjeta }}</td>
                            <td>{{ $b->nombre }} {{ $b->apellido_paterno }} {{ $b->apellido_materno }}</td>
                            <td>{{ optional($b->municipio)->nombre }}</td>
                            <td>{{ $b->seccional }}</td>
                            <td>{{ optional($b->creador)->name }}</td>
                            <td>
                                @if($b->is_draft)
                                    <span class="badge bg-warning">Borrador</span>
                                @else
                                    <span class="badge bg-success">Registrado</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('encargado.beneficiarios.show', $b) }}">Ver</a>
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
