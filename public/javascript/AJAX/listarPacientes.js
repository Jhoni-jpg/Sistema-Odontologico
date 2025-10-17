const fetchFunction_searchPatient = async () => {
    await fetch('asignar/getPatient', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status: 'ok' })
    }).then(async (res) => {
        const contentType = res.headers.get("Content-Type") || "";
        const status = res.status;
        const clone = res.clone();

        if (!res.ok || !contentType.includes("application/json")) {
            const htmlError = await clone.text();

            console.group("🚨 Error de respuesta inesperada");
            console.log("📄 Content-Type:", contentType);
            console.log("📊 Status:", status);
            console.log("🧾 HTML recibido:\n", htmlError);
            console.groupEnd();

            throw new Error(`Respuesta inválida (${status})`);
        } else {
            return res.json();
        }
    }).then(data => {
        if (data.status == 'ok') {
            reactiveSearch(data.message);
        } else if (data.status == 'error') {
            console.log(data.message);
        }
    }).catch(err => {
        console.group("❌ Error en la petición");
        console.error("🧠 Mensaje:", err.message);
        console.error("📚 Stack:", err.stack);
        console.groupEnd();
    });
}

function reactiveSearch(patient) {
    const documentoInput = document.getElementById('documento_paciente');
    const patientResults = document.getElementById('patientResults');
    const patientInfo = document.getElementById('patientInfo');
    const patientName = document.getElementById('patientName');

    documentoInput.addEventListener('input', function () {
        const query = this.value.trim();

        if (query.length >= 3) {
            const filteredPatients = patient.filter(patient =>
                patient.pacidentificacion.includes(query) ||
                patient.pacnombres.toLowerCase().includes(query.toLowerCase())
            );

            if (filteredPatients.length > 0) {
                patientResults.innerHTML = filteredPatients.map(patient =>
                    `<div class="search-result-item" data-id="${patient.id}" data-documento="${patient.pacidentificacion}" data-nombre="${patient.pacnombres}">
                            <strong>${patient.pacidentificacion}</strong> - ${patient.pacnombres}
                        </div>`
                ).join('');
                patientResults.style.display = 'block';
                patientResults.style.display = 'absolute';
                patientResults.style.top = '0px';
                patientResults.style.right = '0px';
            } else {
                patientResults.innerHTML = '<div class="search-result-item">No se encontraron pacientes</div>';
                patientResults.style.display = 'block';
                patientResults.style.display = 'absolute';
                patientResults.style.top = '0px';
                patientResults.style.right = '0px';
            }
        } else {
            patientResults.style.display = 'none';
            patientInfo.style.display = 'none';
        }
    });

    patientResults.addEventListener('click', function (e) {
        const item = e.target.closest('.search-result-item');
        if (item && item.dataset.id) {
            documentoInput.value = item.dataset.documento;
            patientName.textContent = item.dataset.nombre;
            patientInfo.style.display = 'flex';
            patientResults.style.display = 'none';
        }
    });
}

let timeout = null;

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('documento_paciente').addEventListener('input', function () {
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            fetchFunction_searchPatient();
        }, 1000);
    });
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('.search-container')) {
        patientResults.style.display = 'none';
    }
});