{{-- resources/views/usermanagement/partials/scripts.blade.php --}}
@php
    $badgeActiveIcon = trim(view('components.ui.icon', [
        'name' => 'exito',
        'size' => 'xs',
        'class' => 'w-3 h-3 mr-1'
    ])->render());
    $badgeInactiveIcon = trim(view('components.ui.icon', [
        'name' => 'eliminar',
        'size' => 'xs',
        'class' => 'w-3 h-3 mr-1'
    ])->render());
    $spinnerIcon = trim(view('components.ui.icon', [
        'name' => 'refrescar',
        'size' => 'sm',
        'class' => 'animate-spin w-5 h-5'
    ])->render());
    $notificationSuccessIcon = trim(view('components.ui.icon', [
        'name' => 'exito',
        'size' => 'sm',
        'class' => 'text-white'
    ])->render());
    $notificationErrorIcon = trim(view('components.ui.icon', [
        'name' => 'eliminar',
        'size' => 'sm',
        'class' => 'text-white'
    ])->render());
    $notificationInfoIcon = trim(view('components.ui.icon', [
        'name' => 'info',
        'size' => 'sm',
        'class' => 'text-white'
    ])->render());
@endphp
<script>
    /**
     * User Management JavaScript
     * Handles all client-side interactions for user management
     */

    const badgeActiveIcon = {!! json_encode($badgeActiveIcon) !!};
    const badgeInactiveIcon = {!! json_encode($badgeInactiveIcon) !!};
    const spinnerIcon = {!! json_encode($spinnerIcon) !!};
    const notificationIcons = {
        success: {!! json_encode($notificationSuccessIcon) !!},
        error: {!! json_encode($notificationErrorIcon) !!},
        info: {!! json_encode($notificationInfoIcon) !!}
    };

    // ============================================
    // HELPER: Get CSRF Token
    // ============================================
    function getCsrfToken() {
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            return metaToken.content;
        }

        const inputToken = document.querySelector('input[name="_token"]');
        if (inputToken) {
            return inputToken.value;
        }

        console.error('CSRF token not found');
        return null;
    }

    // ============================================
    // MODAL HANDLERS
    // ============================================

    /**
     * Open approval modal with user data
     */
    function openApprovalModal(userId, userName, userEmail) {
        console.log('🔵 Opening approval modal for user:', {
            userId,
            userName,
            userEmail
        });

        const userIdInput = document.getElementById('approve-user-id');
        const userNameEl = document.getElementById('approve-user-name');
        const userEmailEl = document.getElementById('approve-user-email');
        const avatarEl = document.getElementById('approve-user-avatar');

        if (userIdInput) userIdInput.value = userId;
        if (userNameEl) userNameEl.textContent = userName;
        if (userEmailEl) userEmailEl.textContent = userEmail;
        if (avatarEl) avatarEl.textContent = userName.charAt(0).toUpperCase();

        const form = document.getElementById('approve-form');
        if (form) {
            form.reset();
            if (userIdInput) userIdInput.value = userId;
        }

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'approve-user-modal',
            bubbles: true,
            composed: true
        }));
    }

    /**
     * Open edit role modal with user data
     */
    function openEditRoleModal(userId, userName, currentRole, currentArea, isActive = true) {
        console.log('📝 Opening edit modal for user:', { userId, userName, currentRole, currentArea, isActive });

        const userIdInput = document.getElementById('edit-user-id');
        const userNameEl = document.getElementById('edit-user-name');
        const avatarEl = document.getElementById('edit-user-avatar');
        const currentRoleEl = document.getElementById('edit-current-role');
        const roleSelect = document.getElementById('edit-role');
        const areaInput = document.getElementById('edit-area');
        const isActiveCheckbox = document.getElementById('edit-is-active');
        const statusLabel = document.getElementById('edit-status-label');
        const statusBadge = document.getElementById('edit-user-status-badge');

        if (userIdInput) userIdInput.value = userId;
        if (userNameEl) userNameEl.textContent = userName;
        if (avatarEl) avatarEl.textContent = userName.charAt(0).toUpperCase();
        if (currentRoleEl) currentRoleEl.textContent = currentRole || 'Sin rol';
        if (roleSelect) roleSelect.value = currentRole || '';
        if (areaInput) areaInput.value = currentArea || '';

        if (isActiveCheckbox) {
            isActiveCheckbox.checked = isActive;
            isActiveCheckbox.disabled = true;
        }

        if (statusLabel) {
            statusLabel.innerHTML = isActive
                ? '<span class="text-green-600 dark:text-green-400 font-semibold">Activo</span>'
                : '<span class="text-red-600 dark:text-red-400 font-semibold">Inactivo</span>';
        }

        if (statusBadge) {
            statusBadge.innerHTML = isActive
                ? `
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                bg-green-500/20 text-green-700 dark:text-green-300
                                border border-green-500/30">
                        ${badgeActiveIcon}
                        Activo
                    </span>
                `
                : `
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                bg-red-500/20 text-red-700 dark:text-red-300
                                border border-red-500/30">
                        ${badgeInactiveIcon}
                        Inactivo
                    </span>
                `;
        }

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'edit-role-modal',
            bubbles: true
        }));
    }

    /**
     * Open reject modal with user data
     */
    function openRejectModal(userId, userName, userEmail) {
        console.log('🔴 Opening reject modal for user:', {
            userId,
            userName,
            userEmail
        });

        const userIdInput = document.getElementById('reject-user-id');
        const userNameEl = document.getElementById('reject-user-name');
        const userEmailEl = document.getElementById('reject-user-email');
        const avatarEl = document.getElementById('reject-user-avatar');

        if (userIdInput) userIdInput.value = userId;
        if (userNameEl) userNameEl.textContent = userName;
        if (userEmailEl) userEmailEl.textContent = userEmail;
        if (avatarEl) avatarEl.textContent = userName.charAt(0).toUpperCase();

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'reject-user-modal',
            bubbles: true
        }));
    }

    /**
     * Open delete modal with user data
     */
    function openDeleteModal(userId, userName, userEmail) {
        console.log('🗑️ Opening delete modal for user:', {
            userId,
            userName,
            userEmail
        });

        const userIdInput = document.getElementById('delete-user-id');
        const userNameEl = document.getElementById('delete-user-name');
        const userEmailEl = document.getElementById('delete-user-email');
        const avatarEl = document.getElementById('delete-user-avatar');

        if (userIdInput) {
            userIdInput.value = userId;
            console.log('  ✓ Delete user ID set:', userId);
        }
        if (userNameEl) {
            userNameEl.textContent = userName;
            console.log('  ✓ Delete user name set:', userName);
        }
        if (userEmailEl) {
            userEmailEl.textContent = userEmail;
            console.log('  ✓ Delete user email set:', userEmail);
        }
        if (avatarEl) {
            avatarEl.textContent = userName.charAt(0).toUpperCase();
            console.log('  ✓ Delete avatar set');
        }

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'delete-user-modal',
            bubbles: true
        }));
    }

    /**
     * Delete user - ahora abre el modal en lugar de confirm
     */
    function deleteUser(userId, userName, userEmail = '') {
        openDeleteModal(userId, userName, userEmail);
    }

    /**
     * Close modal by name
     */
    function closeModal(modalName) {
        console.log('Closing modal:', modalName);
        window.dispatchEvent(new CustomEvent('close-modal', {
            detail: modalName,
            bubbles: true
        }));
    }

    // ============================================
    // API CALLS
    // ============================================

    /**
     * Approve user and assign role
     */
    async function handleApprovalSubmit(e) {
        e.preventDefault();
        console.log('📝 Form submitted - Approving user');

        const form = e.target;
        const formData = new FormData(form);
        const userId = document.getElementById('approve-user-id').value;
        const role = formData.get('role');
        const area = formData.get('area');
        const submitBtn = form.querySelector('button[type="submit"]');

        if (!userId) {
            window.notify?.show('ID de usuario no encontrado', 'error');
            return;
        }

        if (!role) {
            window.notify?.show('Por favor selecciona un rol', 'error');
            return;
        }

        submitBtn.disabled = true;
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            ${spinnerIcon}
            <span>Procesando...</span>
        `;

        try {
            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                throw new Error('CSRF token no encontrado');
            }

            const response = await fetch(`/user-management/${userId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    role: role,
                    area: area || null
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                closeModal('approve-user-modal');
                window.notify?.show(data.message || 'Usuario aprobado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(data.error || data.message || 'Error al aprobar usuario');
            }
        } catch (error) {
            console.error('❌ Error:', error);
            window.notify?.show(error.message || 'Error de conexión', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        }
    }

    /**
     * Update user role and status
     */
    async function submitEditRoleForm(event) {
        event.preventDefault();

        const userId = document.getElementById('edit-user-id').value;
        const role = document.getElementById('edit-role').value;
        const area = document.getElementById('edit-area').value;
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');

        if (!userId || !role) {
            window.notify?.show('Datos incompletos', 'error');
            return;
        }

        console.log('📤 Submitting edit role form:', { userId, role, area });

        submitBtn.disabled = true;
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            ${spinnerIcon}
            <span>Actualizando...</span>
        `;

        try {
            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                throw new Error('CSRF token no encontrado');
            }

            const response = await fetch(`/user-management/${userId}/update-role`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    role: role,
                    area: area || null,
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                closeModal('edit-role-modal');
                window.notify?.show(data.message || 'Usuario actualizado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(data.error || data.message || 'Error al actualizar usuario');
            }
        } catch (error) {
            console.error('❌ Error:', error);
            window.notify?.show(error.message || 'Error de conexión', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        }
    }

    async function confirmBlockUser(userId, userName, button) {
        if (!userId) {
            window.notify?.show('ID de usuario inválido', 'error');
            return;
        }

        if (!window.confirm(`¿Bloquear a ${userName}? Esta acción suspenderá su acceso.`)) {
            return;
        }

        await handleBlockToggle(`/user-management/${userId}/block`, button, `Usuario ${userName} bloqueado correctamente.`);
    }

    async function confirmUnblockUser(userId, userName, button) {
        if (!userId) {
            window.notify?.show('ID de usuario inválido', 'error');
            return;
        }

        if (!window.confirm(`¿Desbloquear a ${userName}?`)) {
            return;
        }

        await handleBlockToggle(`/user-management/${userId}/unblock`, button, `Usuario ${userName} desbloqueado correctamente.`);
    }

    async function handleBlockToggle(url, button, successMessage) {
        const targetBtn = button || document.createElement('button');
        const originalHTML = targetBtn.innerHTML;
        targetBtn.disabled = true;
        targetBtn.innerHTML = `
            ${spinnerIcon}
            <span>Procesando...</span>
        `;

        try {
            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                throw new Error('CSRF token no encontrado');
            }

            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.notify?.show(data.message || successMessage, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(data.error || data.message || 'Error de conexión');
            }
        } catch (error) {
            console.error('❌ Error:', error);
            window.notify?.show(error.message || 'Error de conexión', 'error');
            targetBtn.disabled = false;
            targetBtn.innerHTML = originalHTML;
        }
    }


    /**
     * Reject user registration
     */
    async function confirmRejectUser() {
        const userId = document.getElementById('reject-user-id').value;
        const userName = document.getElementById('reject-user-name').textContent;
        const confirmBtn = document.getElementById('reject-confirm-btn');

        if (!userId) {
            window.notify?.show('ID de usuario no encontrado', 'error');
            return;
        }

        console.log('🗑️ Rejecting user:', {
            userId,
            userName
        });

        confirmBtn.disabled = true;
        const originalHTML = confirmBtn.innerHTML;
        confirmBtn.innerHTML = `
            ${spinnerIcon}
            <span>Rechazando...</span>
        `;

        try {
            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                throw new Error('CSRF token no encontrado');
            }

            const response = await fetch(`/user-management/${userId}/reject`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                closeModal('reject-user-modal');
                window.notify?.show(data.message || 'Solicitud rechazada', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(data.error || data.message || 'Error al rechazar usuario');
            }
        } catch (error) {
            console.error('Error:', error);
            window.notify?.show(error.message || 'Error al rechazar usuario', 'error');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalHTML;
        }
    }

    /**
     * Confirm delete user permanently (Called from delete modal)
     */
    async function confirmDeleteUser() {
        const userId = document.getElementById('delete-user-id').value;
        const userName = document.getElementById('delete-user-name').textContent;
        const confirmBtn = document.getElementById('delete-confirm-btn');

        if (!userId) {
            window.notify?.show('ID de usuario no encontrado', 'error');
            return;
        }

        console.log('🗑️ Deleting user permanently:', {
            userId,
            userName
        });

        confirmBtn.disabled = true;
        const originalHTML = confirmBtn.innerHTML;
        confirmBtn.innerHTML = `
            ${spinnerIcon}
            <span>Eliminando...</span>
        `;

        try {
            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                throw new Error('CSRF token no encontrado');
            }

            const response = await fetch(`/user-management/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                closeModal('delete-user-modal');
                window.notify?.show(data.message || 'Usuario eliminado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(data.error || data.message || 'Error al eliminar usuario');
            }
        } catch (error) {
            console.error('Error:', error);
            window.notify?.show(error.message || 'Error al eliminar usuario', 'error');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalHTML;
        }
    }

    // ============================================
    // NOTIFICATION SYSTEM
    // ============================================

    /**
     * Show notification toast
     */
    function showNotification(type, message) {
        const colors = {
            success: {
                bg: 'bg-green-500',
                icon: notificationIcons.success
            },
            error: {
                bg: 'bg-red-500',
                icon: notificationIcons.error
            },
            info: {
                bg: 'bg-blue-500',
                icon: notificationIcons.info
            }
        };

        const config = colors[type] || colors.info;

        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${config.bg} text-white px-5 py-3 rounded-lg shadow-2xl z-50
                                  flex items-center gap-3 animate-slide-in-right max-w-md`;
        notification.innerHTML = `
            <div class="shrink-0">${config.icon}</div>
            <p class="text-sm font-medium">${message}</p>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('animate-slide-out-right');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // ============================================
    // UTILITY STYLES
    // ============================================

    if (!document.getElementById('user-mgmt-animations')) {
        const style = document.createElement('style');
        style.id = 'user-mgmt-animations';
        style.textContent = `
            @keyframes slide-in-right {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slide-out-right {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .animate-slide-in-right {
                animation: slide-in-right 0.3s ease-out;
            }
            .animate-slide-out-right {
                animation: slide-out-right 0.3s ease-in;
            }
        `;
        document.head.appendChild(style);
    }

    // ============================================
    // EVENT LISTENERS
    // ============================================

    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Initializing User Management Scripts');

        const approveForm = document.getElementById('approve-form');
        if (approveForm) {
            approveForm.addEventListener('submit', handleApprovalSubmit);
            console.log('✓ Approve form listener attached');
        }

        const editRoleForm = document.getElementById('edit-role-form');
        if (editRoleForm) {
            editRoleForm.addEventListener('submit', submitEditRoleForm);
            console.log('✓ Edit role form listener attached');
        }

        console.log('✅ User Management Scripts Loaded');
        console.log('🔑 CSRF Token available:', !!getCsrfToken());
    });
</script>
