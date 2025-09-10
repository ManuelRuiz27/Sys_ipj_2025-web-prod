<x-app-layout>
<div class="container py-4">
  <h1 class="h4 mb-3">Mis pacientes asignados</h1>

  <div class="card">
    <div class="card-body">
      <div class="row g-2 mb-3">
        <div class="col-12 col-md-6">
          <input type="text" id="search" class="form-control" placeholder="Buscar beneficiario…">
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-sm align-middle" id="tabla">
          <thead class="table-light">
            <tr>
              <th>Nombre</th>
              <th>Teléfono</th>
              <th>Seccional</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function render(rows) {
    const tbody = document.querySelector('#tabla tbody');
    const q = document.getElementById('search').value.toLowerCase();
    tbody.innerHTML = rows
      .filter(r => (r.nombre+" "+r.apellido_paterno+" "+r.apellido_materno).toLowerCase().includes(q))
      .map(r => {
        const nombre = `${r.nombre} ${r.apellido_paterno} ${r.apellido_materno}`;
        return `<tr>
          <td>${nombre}</td>
          <td>${r.telefono||''}</td>
          <td>${r.seccional||''}</td>
          <td>
            <a class="btn btn-sm btn-primary" href="/s360/psico/paciente/${r.id}/show">Abrir</a>
            <a class="btn btn-sm btn-outline-secondary" href="/s360/psico/sesiones/${r.id}/show">Historial</a>
          </td>
        </tr>`;
      }).join('');
  }
  document.addEventListener('DOMContentLoaded', async () => {
    const res = await fetch('/s360/psico/pacientes');
    const data = await res.json();
    const rows = data.items || [];
    render(rows);
    document.getElementById('search').addEventListener('input', () => render(rows));
  });
  </script>
</x-app-layout>
