@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">🚌 Detalle de Ruta</h2>
            <p class="text-muted mb-0">Consulta la información registrada.</p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.rutas.edit', $ruta->id) }}"
               class="btn btn-warning rounded-3 px-4">
                Editar
            </a>

            <a href="{{ route('admin.rutas.index') }}"
               class="btn btn-light border rounded-3 px-4">
                Volver
            </a>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <div class="row g-4">

                <div class="col-md-4">
                    <small class="text-muted d-block mb-1">Código</small>

                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                        {{ $ruta->codigo }}
                    </span>
                </div>

                <div class="col-md-8">
                    <small class="text-muted d-block mb-1">Nombre</small>
                    <h4 class="fw-bold mb-0">{{ $ruta->nombre }}</h4>
                </div>

                <div class="col-12">
                    <small class="text-muted d-block mb-1">Descripción</small>

                    <div class="bg-light rounded-4 p-3">
                        {{ $ruta->descripcion ?: 'Sin descripción registrada.' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Creada</small>
                    <strong>{{ $ruta->created_at?->format('d/m/Y H:i') }}</strong>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Última actualización</small>
                    <strong>{{ $ruta->updated_at?->format('d/m/Y H:i') }}</strong>
                </div>

            </div>

        </div>
    </div>

</div>

@endsection