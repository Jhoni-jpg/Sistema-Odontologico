
const enviarParametros = async () => {
    const formData = new FormData();

    formData.append('fecha', document.getElementById('fecha').value ?? '');
    formData.append('hora', document.querySelector("input[name='hora']:checked").value ?? '');
    formData.append('paciente', document.getElementById('documento_paciente').value ?? '');
    formData.append('medico', document.getElementById('medico').value ?? '');
    formData.append('consultorio', document.getElementById('consultorio').value ?? '');
    formData.append('motivo', document.getElementById('motivo').value ?? '');
    formData.append('observaciones', document.getElementById('observaciones').value ?? '');

    await fetch('asignar/newCita', {
        method: 'POST',
        body: formData,
        headers: { 'Accept': 'application/json; charset=utf-8' }
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
            showToast(data.message, 'success');
            document.getElementById('appointmentForm').reset();
        } else if (data.status == 'error') {
            showToast(data.message, 'danger');
        }
    }).catch(err => {
        
    });
}

function submitFetch() {
    document.getElementById('appointmentForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        await enviarParametros();
    });
}

submitFetch();