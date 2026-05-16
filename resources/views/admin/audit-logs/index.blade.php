<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-slate-800">📋 Registro de Actividades</h2>
        <p class="text-sm text-slate-500 font-bold">Historial de todas las operaciones del sistema</p>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold">Fecha</th>
                            <th class="px-6 py-4 text-left font-bold">Usuario</th>
                            <th class="px-6 py-4 text-left font-bold">Acción</th>
                            <th class="px-6 py-4 text-left font-bold">Modelo</th>
                            <th class="px-6 py-4 text-left font-bold">ID</th>
                            <th class="px-6 py-4 text-left font-bold">IP</th>
                            <th class="px-6 py-4 text-center font-bold">Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                    {{ $log->user?->nombre ?? 'Sistema' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        {{ str_contains($log->action, 'create') ? 'bg-green-100 text-green-800' : '' }}
                                        {{ str_contains($log->action, 'update') ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ str_contains($log->action, 'delete') ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $log->model }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-slate-600">
                                    {{ Str::limit($log->model_id, 12) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.audit-logs.show', $log->id) }}" 
                                       class="text-cyan-600 hover:text-cyan-800 font-bold text-sm">
                                        Ver →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                    No hay registros de actividad aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4 bg-slate-50 border-t">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>