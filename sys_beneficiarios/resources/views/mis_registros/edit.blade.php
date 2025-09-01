<x-app-layout>
    <x-slot name="header"><h2 class="h4 m-0">Editar registro</h2></x-slot>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('mis-registros.update', $beneficiario) }}">
                @csrf
                @method('PUT')
                @include('beneficiarios.partials.form')
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('mis-registros.show', $beneficiario) }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

