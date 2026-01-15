<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if ($errors->any())
            if (typeof showNotification === 'function') {
                showNotification('Corrige los campos marcados y vuelve a intentarlo.', 'error');
            }
        @endif
    });
</script>
