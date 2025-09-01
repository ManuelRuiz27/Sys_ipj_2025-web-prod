<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 m-0">Importar catálogos</h2>
    </x-slot>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('import_log'))
        <div class="alert alert-secondary"><pre class="mb-0 small">{{ session('import_log') }}</pre></div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.catalogos.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Municipios CSV</label>
                        <input type="file" name="municipios" accept=".csv,text/csv" class="form-control @error('municipios') is-invalid @enderror">
                        <div class="form-text">Encabezados: clave,nombre</div>
                        @error('municipios')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Secciones CSV</label>
                        <input type="file" name="secciones" accept=".csv,text/csv" class="form-control @error('secciones') is-invalid @enderror">
                        <div class="form-text">Encabezados: seccional,municipio_clave,distrito_local,distrito_federal</div>
                        @error('secciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SQL opcional</label>
                        <input type="file" name="sql" accept=".sql,.txt" class="form-control @error('sql') is-invalid @enderror">
                        @error('sql')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="fresh" value="1" id="fresh">
                            <label class="form-check-label" for="fresh">Limpiar tablas antes de importar</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-primary" type="submit">Ejecutar importación</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

