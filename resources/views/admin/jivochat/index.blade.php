<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-slate-800">💬 Mensajes JivoChat</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <table class="w-full">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Teléfono</th>
                            <th class="px-4 py-2 text-left">Mensaje</th>
                            <th class="px-4 py-2 text-left">Canal</th>
                            <th class="px-4 py-2 text-left">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $msg)
                            <tr class="border-b">
                                <td class="px-4 py-3">{{ $msg->sender_name }}</td>
                                <td class="px-4 py-3">{{ $msg->sender_phone ?? '-' }}</td>
                                <td class="px-4 py-3">{{ Str::limit($msg->message, 50) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-sm font-bold {{ $msg->channel === 'whatsapp' ? 'bg-green-200 text-green-800' : 'bg-blue-200 text-blue-800' }}">
                                        {{ $msg->channel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $msg->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</x-app-layout>