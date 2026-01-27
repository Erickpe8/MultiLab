@php
    $allowedTypes = ['success', 'error', 'warning', 'info'];
    $notifyPayload = null;
    $sessionNotify = session('notify');

    if ($sessionNotify) {
        if (is_array($sessionNotify)) {
            $message = trim((string) ($sessionNotify['message'] ?? $sessionNotify['text'] ?? ''));
            $type = in_array($sessionNotify['type'] ?? '', $allowedTypes, true) ? $sessionNotify['type'] : 'info';
            $timeout = isset($sessionNotify['timeout']) && is_numeric($sessionNotify['timeout'])
                ? (int) $sessionNotify['timeout']
                : 5000;
        } else {
            $message = trim((string) $sessionNotify);
            $type = 'info';
            $timeout = 5000;
        }

        if ($message !== '') {
            $notifyPayload = [
                'message' => $message,
                'type' => $type,
                'timeout' => $timeout,
            ];
        }
    }

    $statusPayload = null;
    if (!$notifyPayload && $status = session('status')) {
        $statusPayload = [
            'message' => (string) $status,
            'type' => 'success',
            'timeout' => 5000,
        ];
    }

    $errorPayload = null;
    if (!$notifyPayload && !$statusPayload && $errors->any()) {
        $firstError = $errors->first();
        if ($firstError) {
            $errorPayload = [
                'message' => (string) $firstError,
                'type' => 'error',
                'timeout' => 5000,
            ];
        }
    }
@endphp

@if ($notifyPayload || $statusPayload || $errorPayload)
    <script>
        (function () {
            const dispatchToast = (payload) => {
                if (!payload?.message) {
                    return;
                }

                const emit = () => window.dispatchEvent(new CustomEvent('toast', { detail: payload }));

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', emit, { once: true });
                } else {
                    emit();
                }
            };

            @if ($notifyPayload)
                dispatchToast(@json($notifyPayload));
            @elseif ($statusPayload)
                dispatchToast(@json($statusPayload));
            @elseif ($errorPayload)
                dispatchToast(@json($errorPayload));
            @endif
        })();
    </script>
@endif
