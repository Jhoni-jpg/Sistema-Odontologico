
document.getElementById('pacfechanacimiento').max = new Date().toISOString().split('T')[0];


document.addEventListener('click', function (e) {
    if (window.innerWidth <= 768) {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.querySelector('.sidebar-toggle');

        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    }
});

document.getElementById('patientForm').addEventListener('submit', function (e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.classList.add('loading');

    setTimeout(() => {
        submitBtn.classList.remove('loading');
    }, 2000);
});

document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('focus', function () {
        this.parentElement.style.transform = 'scale(1.02)';
        this.parentElement.style.transition = 'transform 0.2s ease';
    });

    input.addEventListener('blur', function () {
        this.parentElement.style.transform = 'scale(1)';
    });
});

document.querySelectorAll('.btn').forEach(button => {
    button.addEventListener('click', function (e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;

        this.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});

const style = document.createElement('style');
style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
document.head.appendChild(style);

document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('input', function () {
        const icon = this.parentElement.querySelector('i');

        if (this.value && this.checkValidity()) {
            icon.style.color = 'var(--success-color)';
            this.style.borderColor = 'var(--success-color)';
        } else if (this.value && !this.checkValidity()) {
            icon.style.color = 'var(--error-color)';
            this.style.borderColor = 'var(--error-color)';
        } else {
            icon.style.color = '';
            this.style.borderColor = '';
        }
    });
});

window.addEventListener('load', function () {
    const formCard = document.querySelector('.form-card');
    if (formCard) {
        formCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

document.querySelector('button[type="reset"]').addEventListener('click', function (e) {
    if (!confirm('¿Está seguro de que desea limpiar todos los campos del formulario?')) {
        e.preventDefault();
    }
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.remove('active');
    }
});

const currentPath = window.location.pathname;
document.querySelectorAll('aside a').forEach(link => {
    if (link.getAttribute('href') === currentPath ||
        (currentPath.includes('paciente') && link.getAttribute('href') === 'paciente')) {
        link.classList.add('active');
    }
});