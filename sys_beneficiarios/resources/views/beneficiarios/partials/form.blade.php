@php($b = $beneficiario ?? null)
@if ($errors->any())
    <div class="alert alert-danger"><strong>Revisa el formulario</strong></div>
@endif

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Folio tarjeta</label>
        <input name="folio_tarjeta" value="{{ old('folio_tarjeta', $b->folio_tarjeta ?? '') }}" class="form-control @error('folio_tarjeta') is-invalid @enderror" required>
        @error('folio_tarjeta')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Nombre</label>
        <input name="nombre" value="{{ old('nombre', $b->nombre ?? '') }}" class="form-control @error('nombre') is-invalid @enderror" required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Apellido paterno</label>
        <input name="apellido_paterno" value="{{ old('apellido_paterno', $b->apellido_paterno ?? '') }}" class="form-control @error('apellido_paterno') is-invalid @enderror" required>
        @error('apellido_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Apellido materno</label>
        <input name="apellido_materno" value="{{ old('apellido_materno', $b->apellido_materno ?? '') }}" class="form-control @error('apellido_materno') is-invalid @enderror" required>
        @error('apellido_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">CURP</label>
        <input name="curp" value="{{ old('curp', $b->curp ?? '') }}" maxlength="18" minlength="18" class="form-control text-uppercase @error('curp') is-invalid @enderror" required>
        @error('curp')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Fecha nacimiento</label>
        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', isset($b)? optional($b->fecha_nacimiento)->format('Y-m-d') : '') }}" class="form-control @error('fecha_nacimiento') is-invalid @enderror" required>
        @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2">
        <label class="form-label">Edad (auto)</label>
        <input type="number" name="edad" value="{{ old('edad', $b->edad ?? '') }}" class="form-control" readonly>
    </div>
    <div class="col-md-2">
        <label class="form-label">Sexo</label>
        <select name="sexo" class="form-select @error('sexo') is-invalid @enderror">
            <option value="">—</option>
            @foreach(['M'=>'M','F'=>'F','X'=>'X'] as $key=>$val)
                <option value="{{ $key }}" @selected(old('sexo', $b->sexo ?? '')==$key)>{{ $val }}</option>
            @endforeach
        </select>
        @error('sexo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2 d-flex align-items-center">
        <div class="form-check mt-4">
            <input type="hidden" name="discapacidad" value="0">
            <input class="form-check-input" type="checkbox" name="discapacidad" value="1" @checked(old('discapacidad', $b->discapacidad ?? false))>
            <label class="form-check-label">Discapacidad</label>
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label">ID INE</label>
        <input name="id_ine" value="{{ old('id_ine', $b->id_ine ?? '') }}" class="form-control @error('id_ine') is-invalid @enderror" required>
        @error('id_ine')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Teléfono (10 dígitos)</label>
        <input name="telefono" value="{{ old('telefono', $b->telefono ?? '') }}" class="form-control @error('telefono') is-invalid @enderror" required>
        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Municipio</label>
        <select name="municipio_id" class="form-select @error('municipio_id') is-invalid @enderror" required>
            <option value="">—</option>
            @foreach($municipios as $id=>$nombre)
                <option value="{{ $id }}" @selected(old('municipio_id', $b->municipio_id ?? '')==$id)>{{ $nombre }}</option>
            @endforeach
        </select>
        @error('municipio_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Seccional</label>
        <input name="seccional" value="{{ old('seccional', $b->seccional ?? '') }}" class="form-control @error('seccional') is-invalid @enderror" required>
        @error('seccional')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Distrito local</label>
        <input name="distrito_local" value="{{ old('distrito_local', $b->distrito_local ?? '') }}" class="form-control @error('distrito_local') is-invalid @enderror" required>
        @error('distrito_local')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Distrito federal</label>
        <input name="distrito_federal" value="{{ old('distrito_federal', $b->distrito_federal ?? '') }}" class="form-control @error('distrito_federal') is-invalid @enderror" required>
        @error('distrito_federal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    
</div>

<hr class="my-4">
<h5>Domicilio</h5>
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Calle</label>
        <input name="domicilio[calle]" value="{{ old('domicilio.calle', $domicilio->calle ?? '') }}" class="form-control @error('domicilio.calle') is-invalid @enderror">
        @error('domicilio.calle')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2">
        <label class="form-label">Número ext</label>
        <input name="domicilio[numero_ext]" value="{{ old('domicilio.numero_ext', $domicilio->numero_ext ?? '') }}" class="form-control @error('domicilio.numero_ext') is-invalid @enderror">
        @error('domicilio.numero_ext')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2">
        <label class="form-label">Número int</label>
        <input name="domicilio[numero_int]" value="{{ old('domicilio.numero_int', $domicilio->numero_int ?? '') }}" class="form-control @error('domicilio.numero_int') is-invalid @enderror">
        @error('domicilio.numero_int')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Colonia</label>
        <input name="domicilio[colonia]" value="{{ old('domicilio.colonia', $domicilio->colonia ?? '') }}" class="form-control @error('domicilio.colonia') is-invalid @enderror">
        @error('domicilio.colonia')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Municipio (texto)</label>
        <input name="domicilio[municipio]" value="{{ old('domicilio.municipio', $domicilio->municipio ?? '') }}" class="form-control @error('domicilio.municipio') is-invalid @enderror">
        @error('domicilio.municipio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2">
        <label class="form-label">CP</label>
        <input name="domicilio[codigo_postal]" value="{{ old('domicilio.codigo_postal', $domicilio->codigo_postal ?? '') }}" class="form-control @error('domicilio.codigo_postal') is-invalid @enderror">
        @error('domicilio.codigo_postal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Seccional</label>
        <input name="domicilio[seccional]" value="{{ old('domicilio.seccional', $domicilio->seccional ?? '') }}" class="form-control @error('domicilio.seccional') is-invalid @enderror">
        @error('domicilio.seccional')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

