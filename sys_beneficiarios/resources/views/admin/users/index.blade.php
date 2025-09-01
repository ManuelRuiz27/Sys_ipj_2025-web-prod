<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 m-0">Usuarios</h2>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">Nuevo usuario</a>
        </div>
    </x-slot>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.usuarios.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Sin usuarios</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="card-footer">{{ $users->links() }}</div>
        @endif
    </div>
</x-app-layout>

