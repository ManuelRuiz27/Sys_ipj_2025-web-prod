<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="h4 m-0">Domicilios</h2>
            <a href="{{ route('domicilios.create') }}" class="btn btn-primary">Nuevo</a>
        </div>
    </x-slot>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form class="row g-2 mb-3" method="GET">
        <div class="col-auto">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar calle, colonia, municipio, CP o seccional">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Beneficiario</th>
                        <th>Calle</th>
                        <th>Colonia</th>
                        <th>Municipio</th>
                        <th>CP</th>
                        <th>Seccional</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($domicilios as $d)
                    <tr>
                        <td>{{ optional($d->beneficiario)->nombre }} {{ optional($d->beneficiario)->apellido_paterno }}</td>
                        <td>{{ $d->calle }} {{ $d->numero_ext }}{{ $d->numero_int? ' Int '.$d->numero_int:'' }}</td>
                        <td>{{ $d->colonia }}</td>
                        <td>{{ $d->municipio }}</td>
                        <td>{{ $d->codigo_postal }}</td>
                        <td>{{ $d->seccional }}</td>
                        <td class="text-end">
                            <a href="{{ route('domicilios.edit', $d) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                            <form action="{{ route('domicilios.destroy', $d) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar domicilio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Sin registros</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($domicilios->hasPages())
            <div class="card-footer">{{ $domicilios->links() }}</div>
        @endif
    </div>
</x-app-layout>

