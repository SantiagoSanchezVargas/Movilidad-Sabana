@extends('layouts.app')

@section('title', 'Registrar Conductor')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h2 class="mb-4">Nuevo Conductor</h2>
            <form action="/api/conductores" method="POST" id="formConductor">
                <div class="mb-3">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Número de Licencia</label>
                    <input type="text" name="licencia" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Guardar Conductor</button>
            </form>
            <div id="mensaje" class="mt-3 alert d-none"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('formConductor').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        const alertDiv = document.getElementById('mensaje');

        try {
            const response = await fetch('/api/conductores', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(data)
            });

            if(response.ok) {
                alertDiv.className = "mt-3 alert alert-success";
                alertDiv.innerText = "¡Conductor registrado exitosamente!";
                alertDiv.classList.remove('d-none');
                e.target.reset();
            } else {
                throw new Error();
            }
        } catch (error) {
            alertDiv.className = "mt-3 alert alert-danger";
            alertDiv.innerText = "Error al registrar. Revisa si la licencia ya existe.";
            alertDiv.classList.remove('d-none');
        }
    });
</script>
@endsection