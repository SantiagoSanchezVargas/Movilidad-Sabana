<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-slate-800">📄 Detalles de Actividad</h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Info General -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Fecha</label>
                        <p class="text-lg text-slate-800">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Usuario</label>
                        <p class="text-lg text-slate-800">{{ $log->user?->nombre ?? 'Sistema' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Acción</label>
                        <p class="text-lg text-slate-800">{{ str_replace('_', ' ', $log->action) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Modelo</label>
                        <p class="text-lg text-slate-800">{{ $log->model }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">ID del Registro</label>
                        <p class="text-lg font-mono text-slate-800">{{ $log->model_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">IP Address</label>
                        <p class="text-lg text-slate-800">{{ $log->ip_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Valores Anteriores -->
            @if($log->old_values)
                <div class="bg-red-50 rounded-lg shadow-lg p-8 mb-6 border-l-4 border-red-500">
                    <h3 class="text-xl font-bold text-red-800 mb-4">❌ Valores Anteriores</h3>
                    <pre class="bg-white p-4 rounded text-sm overflow-x-auto border border-red-200">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @endif

            <!-- Valores Nuevos -->
            @if($log->new_values)
                <div class="bg-green-50 rounded-lg shadow-lg p-8 mb-6 border-l-4 border-green-500">
                    <h3 class="text-xl font-bold text-green-800 mb-4">✅ Valores Nuevos</h3>
                    <pre class="bg-white p-4 rounded text-sm overflow-x-auto border border-green-200">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @endif

            <!-- User Agent -->
            <div class="bg-slate-100 rounded-lg p-4 text-sm text-slate-600 mb-6">
                <strong>User Agent:</strong> {{ $log->user_agent }}
            </div>

            <!-- Botón Volver -->
            <div class="flex gap-4">
                <a href="{{ route('admin.audit-logs.index') }}" 
                   class="px-6 py-3 bg-slate-600 text-white rounded-lg font-bold hover:bg-slate-700 transition">
                    ← Volver
                </a>
            </div>

        </div>
    </div>
</x-app-layout>