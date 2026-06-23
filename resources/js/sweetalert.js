import Swal from 'sweetalert2';

const swalDark = Swal.mixin({
    background: '#1a1a2e',
    color: '#e0e0e0',
    iconColor: '#00f2ff',
    confirmButtonColor: '#00f2ff',
    cancelButtonColor: '#6b7280',
});

window.Swal = swalDark;

document.addEventListener('DOMContentLoaded', () => {
    if (window.__flash) {
        Object.entries(window.__flash).forEach(([key, message]) => {
            const config = {
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                title: message,
            };
            if (key === 'error') {
                config.icon = 'error';
                config.iconColor = '#ef4444';
            } else if (key === 'success') {
                config.icon = 'success';
                config.iconColor = '#22c55e';
            } else {
                config.icon = 'info';
            }
            swalDark.fire(config);
        });
    }
});

window.confirmAction = async function (event, options = {}) {
    event.preventDefault();
    const form = event.target.closest('form');
    const result = await swalDark.fire({
        title: options.title || 'Are you sure?',
        text: options.text || '',
        icon: options.icon || 'warning',
        iconColor: options.icon === 'error' ? '#ef4444' : '#f59e0b',
        showCancelButton: true,
        confirmButtonText: options.confirmText || 'Yes',
        cancelButtonText: options.cancelText || 'Cancel',
        confirmButtonColor: options.confirmColor || '#ef4444',
    });
    if (result.isConfirmed) {
        form.submit();
    }
};
