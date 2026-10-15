import './bootstrap';
import Swal from 'sweetalert2';

// Hacer SweetAlert disponible globalmente
window.Swal = Swal;

// Función para alternar visibilidad de contraseña
window.togglePasswordVisibility = function () {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('passwordEyeIcon');
    if (passwordInput && eyeIcon) {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            eyeIcon.textContent = 'visibility';
        }
    }
};

// Función para alerta de contraseña olvidada con SweetAlert2
window.mostrarContactoSoporte = function () {
    Swal.fire({
        title: '¿Olvidaste tu contraseña?',
        text: 'Por favor contacte al Centro de Soporte Técnico a la Ext. 204 para restablecer su acceso.',
        icon: 'info',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#0059bb',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'px-5 py-2.5 rounded-lg font-bold text-sm'
        }
    });
};