let valorDinamico = 5;
let log = [];

function capitalizarPalabras(texto) {
    return texto.split(" ").map(palabra => palabra.charAt(0).toUpperCase() + palabra.slice(1).toLowerCase()).join(" ");
}

function renderLogs() {
    const containerLogs = document.querySelector('.activity-list');
    const styleLog = new Map();

    styleLog.set('info', ['--item-color: var(--primary-color);', 'fa-solid fa-check-circle']);
    styleLog.set('war', ['--item-color: var(--warning-color);', 'fa-solid fa-edit']);
    styleLog.set('err', ['--item-color: var(--error-color);', 'fa-solid fa-circle-xmark']);

    if (log.length == 0) {
        containerLogs.innerHTML = `
                            <div class="activity-item" style="--item-color: var(--warning-color);">
                                <div class="activity-icon">
                                    <i class="fa-solid fa-spinner"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Sin cambios</div>
                                    <div class="activity-description">No se encontraron cambios realizados recientemente</div>
                                </div>
                            </div>`;
        return;
    }

    const resultado = log.slice(0, valorDinamico);

    if (resultado.length >= log.length) {
        document.querySelector('.logsExpanded').style.display = 'none';
    }


    containerLogs.innerHTML = '';

    resultado.forEach(value => {
        const container = `
                <div class="activity-item" style="${styleLog.get(value['tipo'])[0]}">
                                    <div class="activity-icon">
                                        <i class="${styleLog.get(value['tipo'])[1]}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">${capitalizarPalabras(value['titulo'])}</div>
                                        <div class="activity-description">${value['mensaje']}</div>
                                    </div>
                                    <div class="activity-time">${value['fecha_log']}</div>
                                </div>
                `;

        containerLogs.insertAdjacentHTML('beforeend', container);
    });
}

function dinamicUpdate_logs() {
    document.addEventListener('DOMContentLoaded', async () => {
        log = await getAll_Logs();
        renderLogs();


        document.querySelector('.logsExpanded').addEventListener('click', async () => {
            valorDinamico += 3;
            renderLogs();
        });
    });
}

dinamicUpdate_logs();

function updateDateTime() {
    const now = new Date();

    const dateOptions = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    const dateStr = now.toLocaleDateString('es-CO', dateOptions);
    document.getElementById('currentDate').textContent = dateStr;

    const timeStr = now.toLocaleTimeString('es-CO', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('currentTime').textContent = timeStr;
}

updateDateTime();
setInterval(updateDateTime, 1000);

const observerOptions = {
    threshold: 0.5,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver(function (entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.stat-card, .quick-actions, .recent-activity').forEach(el => {
    observer.observe(el);
});

function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.textContent = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

window.addEventListener('load', function () {
    const statValues = document.querySelectorAll('.stat-value');
    statValues.forEach(stat => {
        const endValue = parseInt(stat.textContent);
        stat.textContent = '0';
        setTimeout(() => {
            animateValue(stat, 0, endValue, 1500);
        }, 300);
    });
});