<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 m-0">Detalle Beneficiario</h2>
            <a href="{{ route('encargado.beneficiarios.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </x-slot>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Datos</div>
                <div class="card-body">
                    @include('beneficiarios.partials.detail', ['beneficiario' => $beneficiario])
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Activity log (últimos 10)</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($activities as $act)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $act->description }} @if($act->event) <span class="text-muted">({{ $act->event }})</span>@endif</span>
                                    <small class="text-muted">{{ $act->created_at->format('Y-m-d H:i') }}</small>
                                </div>
                                @if($act->causer)
                                    <div class="small text-muted">por: {{ optional($act->causer)->name ?? ('ID '.$act->causer_id) }}</div>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Sin eventos</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

