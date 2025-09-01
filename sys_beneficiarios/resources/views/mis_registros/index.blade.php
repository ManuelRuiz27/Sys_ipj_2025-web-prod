<x-app-layout>
    <x-slot name="header"><h2 class="h4 m-0">Mis registros</h2></x-slot>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Folio</th>
                        <th>Nombre</th>
                        <th>Municipio</th>
                        <th>Seccional</th>
                        <th>Estatus</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $b)
                        <tr>
                            <td>{{ $b->folio_tarjeta }}</td>
                            <td>{{ $b->nombre }} {{ $b->apellido_paterno }} {{ $b->apellido_materno }}</td>
                            <td>{{ optional($b->municipio)->nombre }}</td>
                            <td>{{ $b->seccional }}</td>
                            <td>{!! $b->is_draft ? '<span class="badge bg-warning">Borrador</span>' : '<span class="badge bg-success">Final</span>' !!}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('mis-registros.show', $b) }}">Ver</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('mis-registros.edit', $b) }}">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div class="card-footer">{{ $items->links() }}</div>
        @endif
    </div>
</x-app-layout>

