@php
    $type = $block['type'] ?? 'text';
    $roleLabels = [
        'superadmin' => 'Superadmin',
        'aux_admin' => 'Auxiliar admin',
        'docente' => 'Docente',
        'estudiante' => 'Estudiante',
    ];
@endphp

@switch($type)
    @case('text')
        <div class="space-y-1 text-sm text-[var(--text-muted)]">
            @foreach ($block['lines'] as $line)
                <p>{{ $line }}</p>
            @endforeach
        </div>
    @break

    @case('list')
        <ul class="mt-3 space-y-2 text-sm text-[var(--text-muted)]">
            @foreach ($block['items'] as $item)
                <li class="flex gap-2">
                    <span class="text-[var(--accent)]">•</span>
                    <span>{{ $item }}</span>
                </li>
            @endforeach
        </ul>
    @break

    @case('steps')
        <ol class="mt-3 space-y-3 text-sm text-[var(--text-muted)]">
            @foreach ($block['steps'] as $index => $step)
                <li class="flex gap-3">
                    <span class="flex-shrink-0 rounded-full border border-[var(--border)] px-2 py-1 text-[0.65rem] font-semibold text-[var(--text)]">
                        {{ $index + 1 }}
                    </span>
                    <span>{{ $step }}</span>
                </li>
            @endforeach
        </ol>
    @break

    @case('roles-chip')
        <div class="flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-wide">
            @foreach ($block['roles'] as $role)
                <span class="rounded-full border border-[var(--border)] px-3 py-1 text-[var(--text)]">
                    {{ $roleLabels[$role] ?? ucfirst(str_replace('_', ' ', $role)) }}
                </span>
            @endforeach
        </div>
    @break

@endswitch
