(() => {
    const ICON_IDS = {
        badgeActive: 'user-mgmt-icon-badge-active',
        badgeInactive: 'user-mgmt-icon-badge-inactive',
        spinner: 'user-mgmt-icon-spinner',
        notificationSuccess: 'user-mgmt-icon-notification-success',
        notificationError: 'user-mgmt-icon-notification-error',
        notificationInfo: 'user-mgmt-icon-notification-info',
    };

    let badgeActiveIcon = '';
    let badgeInactiveIcon = '';
    let spinnerIcon = '';
    const notificationIcons = {
        success: '',
        error: '',
        info: '',
    };

    const getIconMarkup = (id) => document.getElementById(id)?.innerHTML?.trim() ?? '';

    const resolveIcons = () => {
        badgeActiveIcon = getIconMarkup(ICON_IDS.badgeActive);
        badgeInactiveIcon = getIconMarkup(ICON_IDS.badgeInactive);
        spinnerIcon = getIconMarkup(ICON_IDS.spinner);
        notificationIcons.success = getIconMarkup(ICON_IDS.notificationSuccess);
        notificationIcons.error = getIconMarkup(ICON_IDS.notificationError);
        notificationIcons.info = getIconMarkup(ICON_IDS.notificationInfo);
    };

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

    function openApprovalModal(userId, userName, userEmail) {
        console.debug('Opening approval modal', { userId, userName, userEmail });

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
            composed: true,
        }));
    }

    function openEditRoleModal(userId, userName, currentRole, currentArea, isActive = true) {
        console.debug('Opening edit modal', { userId, userName, currentRole, currentArea, isActive });

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
                : '<span class="text-[var(--primary)] dark:text-[var(--primary-600)] font-semibold">Inactivo</span>';
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
                                bg-[var(--primary-soft)] text-[var(--primary)] dark:text-[var(--primary-600)]
                                border border-[color-mix(in oklab, var(--primary) 40%, var(--border))]">
                        ${badgeInactiveIcon}
                        Inactivo
                    </span>
                `;
        }

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'edit-role-modal',
            bubbles: true,
        }));
    }

    function openRejectModal(userId, userName, userEmail) {
        console.debug('Opening reject modal', { userId, userName, userEmail });

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
            bubbles: true,
        }));
    }

    function closeModal(modalName) {
        console.debug('Closing modal', modalName);
        window.dispatchEvent(new CustomEvent('close-modal', {
            detail: modalName,
            bubbles: true,
        }));
    }

    async function handleApprovalSubmit(e) {
        e.preventDefault();
        console.debug('Submitting approval form');

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

        if (submitBtn) {
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
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        role,
                        area: area || null,
                    }),
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
                console.error('Error approving user', error);
                window.notify?.show(error.message || 'Error de conexión', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHTML;
            }
        }
    }

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

        console.debug('Submitting edit role form', { userId, role, area });

        if (submitBtn) {
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
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        role,
                        area: area || null,
                    }),
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
                console.error('Error updating user role', error);
                window.notify?.show(error.message || 'Error de conexión', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHTML;
            }
        }
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
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.notify?.show(data.message || successMessage, 'success');
                setTimeout(() => location.reload(), 1500);
                return true;
            } else {
                throw new Error(data.error || data.message || 'Error de conexión');
            }
        } catch (error) {
            console.error('Error toggling block state', error);
            window.notify?.show(error.message || 'Error de conexión', 'error');
            targetBtn.disabled = false;
            targetBtn.innerHTML = originalHTML;
            return false;
        }
    }

    let blockTargetName = '';

    const confirmBlockUser = (userId, userName, userEmail = '') => {
        if (!userId) {
            window.notify?.show('ID de usuario inválido', 'error');
            return;
        }

        blockTargetName = userName || 'este usuario';

        const userIdInput = document.getElementById('block-user-id');
        const userNameEl = document.getElementById('block-user-name');
        const userLabel = document.getElementById('block-confirm-user');
        const avatarEl = document.getElementById('block-user-avatar');
        const userEmailEl = document.getElementById('block-user-email');

        if (userIdInput) userIdInput.value = userId;
        if (userNameEl) userNameEl.textContent = userName;
        if (userLabel) userLabel.textContent = userName;
        if (avatarEl) avatarEl.textContent = userName.charAt(0).toUpperCase();
        if (userEmailEl) userEmailEl.textContent = userEmail || 'No disponible';

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'block-user-modal',
            bubbles: true,
            composed: true,
        }));
    };

    async function handleBlockSubmit(event) {
        event.preventDefault();
        const userId = document.getElementById('block-user-id')?.value;
        const confirmBtn = document.getElementById('block-user-confirm-btn');
        const userName = blockTargetName || document.getElementById('block-user-name')?.textContent || 'este usuario';

        if (!userId) {
            window.notify?.show('ID de usuario inválido', 'error');
            return;
        }

        if (confirmBtn) {
            confirmBtn.disabled = true;
        }

        const success = await handleBlockToggle(`/user-management/${userId}/block`, confirmBtn, `Usuario ${userName} bloqueado correctamente.`);

        if (success) {
            closeModal('block-user-modal');
        }
    }

    const confirmUnblockUser = async (userId, userName, button) => {
        if (!userId) {
            window.notify?.show('ID de usuario inválido', 'error');
            return;
        }

        if (!window.confirm(`¿Desbloquear a ${userName}?`)) {
            return;
        }

        await handleBlockToggle(`/user-management/${userId}/unblock`, button, `Usuario ${userName} desbloqueado correctamente.`);
    };

    async function confirmRejectUser() {
        const userId = document.getElementById('reject-user-id').value;
        const userName = document.getElementById('reject-user-name').textContent;
        const confirmBtn = document.getElementById('reject-confirm-btn');

        if (!userId) {
            window.notify?.show('ID de usuario no encontrado', 'error');
            return;
        }

        console.debug('Rejecting user', { userId, userName });

        if (confirmBtn) {
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
                        'Accept': 'application/json',
                    },
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
                console.error('Error rejecting user', error);
                window.notify?.show(error.message || 'Error al rechazar usuario', 'error');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = originalHTML;
            }
        }
    }

    function showNotification(type, message) {
        const colors = {
            success: {
                bg: 'bg-green-500',
                icon: notificationIcons.success,
            },
            error: {
                bg: 'bg-[var(--primary)]',
                icon: notificationIcons.error,
            },
            info: {
                bg: 'bg-blue-500',
                icon: notificationIcons.info,
            },
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

    function ensureAnimationStyles() {
        if (document.getElementById('user-mgmt-animations')) {
            return;
        }

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

    function attachEventListeners() {
        const approveForm = document.getElementById('approve-form');
        if (approveForm) {
            approveForm.addEventListener('submit', handleApprovalSubmit);
        }

        const editRoleForm = document.getElementById('edit-role-form');
        if (editRoleForm) {
            editRoleForm.addEventListener('submit', submitEditRoleForm);
        }
        const blockForm = document.getElementById('block-user-form');
        if (blockForm) {
            blockForm.addEventListener('submit', handleBlockSubmit);
        }
    }

    function exposeGlobals() {
        window.openApprovalModal = openApprovalModal;
        window.openEditRoleModal = openEditRoleModal;
        window.openRejectModal = openRejectModal;
        window.confirmBlockUser = confirmBlockUser;
        window.confirmUnblockUser = confirmUnblockUser;
        window.confirmRejectUser = confirmRejectUser;
        window.closeModal = closeModal;
        window.showUserMgmtNotification = showNotification;
    }

    let initialized = false;
    const init = () => {
        if (initialized) {
            return;
        }

        initialized = true;
        resolveIcons();
        ensureAnimationStyles();
        attachEventListeners();
        exposeGlobals();
        console.debug('User management scripts initialized');
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
