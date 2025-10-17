
const enviarParametros = async () => {
    const formData = new FormData();

    formData.append('identificacion', document.getElementById('pacidentificacion').value ?? '');
    formData.append('nombres', document.getElementById('pacnombres').value ?? '');
    formData.append('apellidos', document.getElementById('pacapellidos').value ?? '');
    formData.append('fechanacimiento', document.getElementById('pacfechanacimiento').value ?? '');
    formData.append('sexo', document.querySelector("input[name='sexo']:checked").value ?? '');

    await fetch('/paciente/newPatient', {
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

            document.getElementById('patientForm').reset();
        } else if (data.status == 'error') {
            showToast(data.message, 'danger');
        }
    }).catch(err => {
        console.group("❌ Error en la petición");
        console.error("🧠 Mensaje:", err.message);
        console.error("📚 Stack:", err.stack);
        console.groupEnd();
    });
}

function submitFetch() {
    document.getElementById('patientForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        await enviarParametros();
    });
}

submitFetch();