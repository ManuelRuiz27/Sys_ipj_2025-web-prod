<x-app-layout>
    <x-slot name="header"><h2 class="h4 m-0">Panel de Encargado</h2></x-slot>

    <div data-kpis-url="{{ route('encargado.kpis') }}" data-export-url="{{ route('encargado.beneficiarios.export') }}">
        <div class="card mb-3">
            <div class="card-body">
                <form id="kpiFilters" class="row gy-2 gx-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Municipio</label>
                        <select name="municipio_id" class="form-select">
                            <option value="">—</option>
                            @foreach($municipios as $id=>$nombre)
                                <option value="{{ $id }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Seccional</label>
                        <input name="seccional" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Capturista</label>
                        <select name="capturista" class="form-select">
                            <option value="">—</option>
                            @foreach($capturistas as $u)
                                <option value="{{ $u->uuid }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Desde</label>
                        <input type="date" name="from" class="form-control">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Hasta</label>
                        <input type="date" name="to" class="form-control">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">—</option>
                            <option value="borrador">Borrador</option>
                            <option value="registrado">Registrado</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 ms-auto text-end">
                        <a id="exportCsvBtn" href="#" class="btn btn-outline-success me-2">Exportar CSV</a>
                        <button class="btn btn-primary" type="submit">Aplicar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted">Total</div><div class="h3" id="kpiTotal">—</div></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted">Borrador</div><div class="h3" id="kpiBorrador">—</div></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted">Registrado</div><div class="h3" id="kpiRegistrado">—</div></div></div></div>
        </div>

        <div class="row g-3">
            <div class="col-lg-6"><div class="card"><div class="card-header">Por municipio</div><div class="card-body"><canvas id="chartByMunicipio" height="180"></canvas></div></div></div>
            <div class="col-lg-6"><div class="card"><div class="card-header">Por seccional (Top 10)</div><div class="card-body"><canvas id="chartBySeccional" height="180"></canvas></div></div></div>
            <div class="col-lg-6"><div class="card"><div class="card-header">Por capturista (Top 10)</div><div class="card-body"><canvas id="chartByCapturista" height="180"></canvas></div></div></div>
            <div class="col-lg-6"><div class="card"><div class="card-header">Estado</div><div class="card-body"><canvas id="chartEstado" height="180"></canvas></div></div></div>
            <div class="col-lg-6"><div class="card"><div class="card-header">Esta semana</div><div class="card-body"><canvas id="chartWeek" height="180"></canvas></div></div></div>
            <div class="col-lg-12"><div class="card"><div class="card-header">Últimos 30 días</div><div class="card-body"><canvas id="chart30" height="200"></canvas></div></div></div>
        </div>
    </div>

    @vite(['resources/js/dashboard.js'])
</x-app-layout>
