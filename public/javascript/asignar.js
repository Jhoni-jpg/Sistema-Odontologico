// Set min date for appointment (today)
document.getElementById('fecha').min = new Date().toISOString().split('T')[0];

// Update available time slots based on doctor and date
const medicoSelect = document.getElementById('medico');
const fechaInput = document.getElementById('fecha');
const consultorioSelect = document.getElementById('consultorio');
const timeSlots = document.querySelectorAll('.time-slot');
const timeAvailability = document.getElementById('timeAvailability');
const doctorAvailability = document.getElementById('doctorAvailability');
const officeAvailability = document.getElementById('officeAvailability');

function updateTimeSlots() {
    const selectedDoctor = medicoSelect.value;
    const selectedDate = fechaInput.value;
    const selectedOffice = consultorioSelect.value;

    if (selectedDoctor && selectedDate) {
        // Simulate some occupied time slots
        const occupiedSlots = ['08:30', '10:00', '15:00', '16:30'];

        timeSlots.forEach(slot => {
            const timeValue = slot.querySelector('input').value;
            const isOccupied = occupiedSlots.includes(timeValue);

            if (isOccupied) {
                slot.classList.add('disabled');
                slot.querySelector('input').disabled = true;
            } else {
                slot.classList.remove('disabled');
                slot.querySelector('input').disabled = false;
            }
        });

        timeAvailability.innerHTML = '<i class="fa-solid fa-clock"></i><span>Horarios actualizados - Los horarios en gris no están disponibles</span>';
        timeAvailability.className = 'availability-indicator available';
        timeAvailability.style.display = 'flex';
    } else {
        timeAvailability.innerHTML = '<i class="fa-solid fa-clock"></i><span>Seleccione fecha y médico para ver horarios disponibles</span>';
        timeAvailability.className = 'availability-indicator limited';
        timeAvailability.style.display = 'flex';

        // Reset all time slots
        timeSlots.forEach(slot => {
            slot.classList.remove('disabled');
            slot.querySelector('input').disabled = false;
        });
    }
}

// Show doctor availability
medicoSelect.addEventListener('change', function () {
    if (this.value) {
        doctorAvailability.style.display = 'flex';
        updateTimeSlots();
    } else {
        doctorAvailability.style.display = 'none';
    }
});

// Show office availability
consultorioSelect.addEventListener('change', function () {
    if (this.value) {
        officeAvailability.style.display = 'flex';
        updateTimeSlots();
    } else {
        officeAvailability.style.display = 'none';
    }
});

fechaInput.addEventListener('change', updateTimeSlots);

// Form submission handler with loading state
document.getElementById('appointmentForm').addEventListener('submit', function (e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.classList.add('loading');

    // Validate that all required fields are filled
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = 'var(--error-color)';
        } else {
            field.style.borderColor = 'var(--success-color)';
        }
    });

    if (!isValid) {
        submitBtn.classList.remove('loading');
        // Show error message
        const flashContainer = document.querySelector('.flash-container');
        const errorAlert = document.createElement('div');
        errorAlert.className = 'alert alert-danger alert-dismissible fade show';
        errorAlert.innerHTML = `
                    Por favor, complete todos los campos obligatorios.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
        flashContainer.appendChild(errorAlert);

        setTimeout(() => {
            errorAlert.remove();
        }, 5000);

        e.preventDefault();
        return;
    }

    // Remove loading state after form submission
    setTimeout(() => {
        submitBtn.classList.remove('loading');
    }, 2000);
});

// Add ripple effect to buttons
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

// Add ripple animation
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

// Real-time validation feedback
document.querySelectorAll('.form-input, .form-select').forEach(input => {
    input.addEventListener('input', function () {
        const icon = this.parentElement.querySelector('i');

        if (this.value && this.checkValidity()) {
            if (icon) icon.style.color = 'var(--success-color)';
            this.style.borderColor = 'var(--success-color)';
        } else if (this.value && !this.checkValidity()) {
            if (icon) icon.style.color = 'var(--error-color)';
            this.style.borderColor = 'var(--error-color)';
        } else {
            if (icon) icon.style.color = '';
            this.style.borderColor = '';
        }
    });
});

// Enhanced form interactions
document.querySelectorAll('.form-input, .form-select').forEach(input => {
    input.addEventListener('focus', function () {
        this.parentElement.style.transform = 'scale(1.02)';
        this.parentElement.style.transition = 'transform 0.2s ease';
    });

    input.addEventListener('blur', function () {
        this.parentElement.style.transform = 'scale(1)';
    });
});

// Form reset confirmation
document.querySelector('button[type="reset"]').addEventListener('click', function (e) {
    if (!confirm('¿Está seguro de que desea limpiar todos los campos del formulario?')) {
        e.preventDefault();
    } else {
        // Reset all availability indicators
        patientInfo.style.display = 'none';
        doctorAvailability.style.display = 'none';
        officeAvailability.style.display = 'none';
        timeAvailability.style.display = 'none';

        // Reset time slots
        timeSlots.forEach(slot => {
            slot.classList.remove('disabled');
            slot.querySelector('input').disabled = false;
        });
    }
});

// Keyboard navigation for sidebar
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.remove('active');
    }
});

// Auto-dismiss alerts after 5 seconds
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }, 5000);
});