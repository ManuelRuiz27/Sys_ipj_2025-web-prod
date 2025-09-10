@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h5 mb-3">Asignaciones</h1>
  <div class="card mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-12 col-md-3">
          <label class="form-label small">Estado</label>
          <select id="f-status" class="form-select form-select-sm">
            <option value="active" selected>Activas</option>
            <option value="inactive">Inactivas</option>
            <option value="all">Todas</option>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label small">Municipio ID</label>
          <input id="f-mun" type="number" class="form-control form-control-sm" placeholder="Opcional">
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label small">PsicÃ³logo ID</label>
          <input id="f-psi" type="number" class="form-control form-control-sm" placeholder="Opcional">
        </div>
        <div class="col d-flex align-items-end">
          <button id="btn-filtrar" class="btn btn-sm btn-primary">Filtrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>Beneficiario</th>
              <th>PsicÃ³logo</th>
              <th>Municipio</th>
              <th>Asignada</th>
              <th>Estado</th>
              <th style="width:140px">Acciones</th>
            </tr>
          </thead>
          <tbody id="rows"></tbody>
        </table>
      </div>
      <div id="pager" class="small text-muted"></div>
    </div>
  </div>
</div>

<!-- Modal cambiar psicÃ³logo -->
<div class="modal fade" id="modalReassign" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Cambiar psicÃ³logo</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" id="m-ben">
        <input type="hidden" id="m-current">
        <label class="form-label">Nuevo psicÃ³logo</label>
        <select id="m-psi" class="form-select"></select>
        <div class="small text-danger mt-2" id="m-err"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="btn-reassign" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  async function load(page=1){
    const status = document.getElementById('f-status').value;
    const mun = document.getElementById('f-mun').value;
    const psi = document.getElementById('f-psi').value;
    const p = new URLSearchParams({status, page});
    if (mun) p.append('municipio_id', mun);
    if (psi) p.append('psicologo_id', psi);
    document.getElementById("pager").innerHTML = "<div class=\"d-flex align-items-center\"><div class=\"spinner-border spinner-border-sm me-2\" role=\"status\"></div> Cargando…</div>"; const res = await fetch(`/s360/enc360/pacientes?${p.toString()}`);
    const data = await res.json();
    const tbody = document.getElementById('rows');
    tbody.innerHTML = (data.data||[]).map(r => {
      const nombre = `${r.ben_nombre||''} ${r.ben_apellido_paterno||''} ${r.ben_apellido_materno||''}`.trim();
      const estado = r.active ? '<span class="badge bg-success">Activa</span>' : '<span class="badge bg-secondary">Inactiva</span>';
      return `<tr>
        <td>${nombre}</td>
        <td>${r.psicologo_name||''}</td>
        <td>${r.municipio_id||''}</td>
        <td>${(r.assigned_at||'').toString().substring(0,10)}</td>
        <td>${estado}</td>
        <td>
          <button class="btn btn-sm btn-outline-primary" data-action="reassign" data-ben="${r.beneficiario_id}" data-currentpsi="${r.psicologo_id||''}">Cambiar</button>
        </td>
      </tr>`;
    }).join('');
    const pager = document.getElementById('pager');
    pager.textContent = `PÃ¡gina ${data.current_page||1} de ${data.last_page||1} â€¢ ${data.total||0} resultados`;

    // wire buttons
    document.querySelectorAll('button[data-action="reassign"]').forEach(btn => {
      btn.addEventListener('click', async () => {
        const ben = btn.getAttribute('data-ben');
        const current = btn.getAttribute('data-currentpsi') || '';
        document.getElementById('m-ben').value = ben;
        document.getElementById('m-current').value = current;
        await loadPsicologos(current);
        new bootstrap.Modal(document.getElementById('modalReassign')).show();
      });
    });
  }
  document.addEventListener('DOMContentLoaded', ()=>{
    // Prefill from querystring
    const params = new URLSearchParams(location.search);
    if (params.get('psicologo_id')) document.getElementById('f-psi').value = params.get('psicologo_id');
    load();
    document.getElementById('btn-filtrar').addEventListener('click', ()=>load());

    const btnRe = document.getElementById('btn-reassign');
    btnRe.addEventListener('click', async ()=>{
      const ben = document.getElementById('m-ben').value;
      const psi = document.getElementById('m-psi').value;
      const current = document.getElementById('m-current').value;
      const err = document.getElementById('m-err'); err.textContent='';
      if (!psi) { err.textContent='Seleccione un psicÃ³logo'; return; }
      if (current && String(psi) === String(current)) { err.textContent='Seleccione un psicÃ³logo diferente al actual'; return; }
      btnRe.setAttribute('disabled','disabled');
      document.getElementById("pager").innerHTML = "<div class=\"d-flex align-items-center\"><div class=\"spinner-border spinner-border-sm me-2\" role=\"status\"></div> Cargando…</div>"; const res = await fetch(`/s360/enc360/assign/${ben}`, {method:'PUT', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body: JSON.stringify({psicologo_id: psi})});
      if (!res.ok) { const j = await res.json().catch(()=>({message:'Error'})); err.textContent=j.message||'Error'; btnRe.removeAttribute('disabled'); return; }
      bootstrap.Modal.getInstance(document.getElementById('modalReassign')).hide();
      showToast('AsignaciÃ³n actualizada','success');
      btnRe.removeAttribute('disabled');
      load();
    });
  });

  async function loadPsicologos(current=''){
    document.getElementById("pager").innerHTML = "<div class=\"d-flex align-items-center\"><div class=\"spinner-border spinner-border-sm me-2\" role=\"status\"></div> Cargando…</div>"; const res = await fetch('/s360/enc360/psicologos/list');
    const data = await res.json();
    const sel = document.getElementById('m-psi');
    sel.innerHTML = '<option value="">Seleccioneâ€¦</option>' + (data.data||[]).map(r => `<option value="${r.id}" ${String(current)===String(r.id)?'selected':''}>${r.name} (${r.email}) â€” ${r.cargas}</option>`).join('');
  }

  function showToast(message, type='info'){
    const wrap = document.createElement('div');
    wrap.className = 'toast align-items-center text-bg-'+(type==='success'?'success':'primary')+' border-0 position-fixed top-0 end-0 m-3';
    wrap.setAttribute('role','alert'); wrap.setAttribute('aria-live','assertive'); wrap.setAttribute('aria-atomic','true');
    wrap.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.body.appendChild(wrap);
    const t = new bootstrap.Toast(wrap, {delay:2500}); t.show();
    wrap.addEventListener('hidden.bs.toast', ()=>wrap.remove());
  }
</script>
@endpush
@endsection



