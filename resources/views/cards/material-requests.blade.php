@php
    use App\Models\MaterialRequest;

    $pendingRequests = MaterialRequest::with(['material', 'requester'])
        ->latest()
        ->take(6)
        ->get();
@endphp

<div class="bg-white dark:bg-gray-900 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-800 p-6 space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-gray-400 font-semibold">Solicitudes</p>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Préstamos de estudiantes</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Últimas solicitudes enviadas desde el catálogo de materiales.</p>
        </div>
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{ $pendingRequests->count() ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-200' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
            {{ $pendingRequests->count() }} registro{{ $pendingRequests->count() === 1 ? '' : 's' }}
        </span>
    </div>

    @if ($pendingRequests->isEmpty())
        <div class="p-6 border border-dashed border-gray-200 dark:border-gray-700 rounded-xl text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">No hay solicitudes pendientes todavía.</p>
            <p class="text-xs mt-2 text-gray-400">Cuando un estudiante envíe un formulario verás el resumen aquí.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 uppercase text-xs tracking-wider">
                        <th class="pb-2">Estudiante</th>
                        <th class="pb-2">Material</th>
                        <th class="pb-2">Cantidad</th>
                        <th class="pb-2">Necesita</th>
                        <th class="pb-2">Devuelve</th>
                        <th class="pb-2">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($pendingRequests as $request)
                    <tr class="text-gray-700 dark:text-gray-200">
                        <td class="py-3">
                            <div class="font-medium">{{ $request->requester?->name ?? '—' }}</div>
                            <div class="text-xs text-gray-500">{{ $request->requester?->email }}</div>
                        </td>
                        <td class="py-3">
                            <div class="font-medium">{{ $request->material?->name ?? 'Material eliminado' }}</div>
                            <div class="text-xs text-gray-500">SKU: {{ $request->material?->sku ?? 'N/A' }}</div>
                        </td>
                        <td class="py-3 font-semibold">{{ $request->quantity }}</td>
                        <td class="py-3 text-xs">{{ optional($request->needed_at)->format('d M Y H:i') }}</td>
                        <td class="py-3 text-xs">{{ optional($request->planned_return_at)->format('d M Y H:i') }}</td>
                        <td class="py-3">
                            @php
                                $statusColors = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-300',
                                    'aprobada' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300',
                                    'rechazada' => 'bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-300',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-end">
            <a href="{{ route('filament.dashboard.resources.material-catalogs.index') }}"
               class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-300">
                Ver catálogo
            </a>
        </div>
    @endif
</div>
