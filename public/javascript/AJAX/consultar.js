const formData = new FormData();

const fetchFunction_getMedics = async () => {
    try {
        const res = await fetch('asignar/getMedics', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: 'ok' })
        });

        const contentType = res.headers.get("Content-Type") || "";
        const status = res.status;

        if (!res.ok || !contentType.includes("application/json")) {
            const htmlError = await res.text();

            console.group("🚨 Error de respuesta inesperada");
            console.log("📄 Content-Type:", contentType);
            console.log("📊 Status:", status);
            console.log("🧾 HTML recibido:\n", htmlError);
            console.groupEnd();

            throw new Error(`Respuesta inválida (${status})`);
        }

        const data = await res.json();

        if (data.status === 'ok') {
            return data.message;
        } else {
            showToast('Ha ocurrido un error inesperado en el listado de médicos', 'danger');
            return [];
        }

    } catch (err) {
        console.group("❌ Error en la petición");
        console.error("🧠 Mensaje:", err.message);
        console.error("📚 Stack:", err.stack);
        console.groupEnd();
        return [];
    }
};

const fetchFunction_getConsultorios = async () => {
    try {
        const res = await fetch('asignar/getConsultorio', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: 'ok' })
        });

        const contentType = res.headers.get("Content-Type") || "";
        const status = res.status;

        if (!res.ok || !contentType.includes("application/json")) {
            const htmlError = await res.text();

            console.group("🚨 Error de respuesta inesperada");
            console.log("📄 Content-Type:", contentType);
            console.log("📊 Status:", status);
            console.log("🧾 HTML recibido:\n", htmlError);
            console.groupEnd();

            throw new Error(`Respuesta inválida (${status})`);
        }

        const data = await res.json();

        if (data.status == 'ok') {
            return data.message;
        } else if (data.status == 'error') {
            showToast('Ha ocurrido un error inesperado en el listado de consultorios', 'danger');
            return [];
        }
    } catch (err) {
        console.group("❌ Error en la petición");
        console.error("🧠 Mensaje:", err.message);
        console.error("📚 Stack:", err.stack);
        console.groupEnd();
    }
}

const fetchFunction_getCitas = async () => {
    try {
        const res = await fetch('consultar/getCitas', {
            headers: { 'Content-Type': 'application/json' }
        });

        const contentType = res.headers.get("Content-Type") || "";
        const status = res.status;

        if (!res.ok || !contentType.includes("application/json")) {
            const htmlError = await res.text();

            console.group("🚨 Error de respuesta inesperada");
            console.log("📄 Content-Type:", contentType);
            console.log("📊 Status:", status);
            console.log("🧾 HTML recibido:\n", htmlError);
            console.groupEnd();

            throw new Error(`Respuesta inválida (${status})`);
        }

        const data = await res.json();

        if (data.status == 'ok') {
            return data.message;
        } else if (data.status == 'error') {
            showToast(data.message, 'danger');
            return [];
        }
    } catch (err) {
        console.group("❌ Error en la petición");
        console.error("🧠 Mensaje:", err.message);
        console.error("📚 Stack:", err.stack);
        console.groupEnd();
    }
}

const fetchFunction_eliminarCita = async (value) => {
    try {
        formData.append('idCita', parseInt(value) ?? '');

        const res = await fetch('consultar/eliminarCita', {
            method: 'POST',
            body: formData
        });

        const contentType = res.headers.get("Content-Type") || "";
        const status = res.status;

        if (!res.ok || !contentType.includes("application/json")) {
            const htmlError = await res.text();

            console.group("🚨 Error de respuesta inesperada");
            console.log("📄 Content-Type:", contentType);
            console.log("📊 Status:", status);
            console.log("🧾 HTML recibido:\n", htmlError);
            console.groupEnd();

            throw new Error(`Respuesta inválida (${status})`);
        }

        const data = await res.json();

        if (data.status == 'ok') {
            showToast(data.message, 'success');

            const fila = document.querySelector(`tr[data-id="${value}"]`);

            if (fila) fila.remove();

            tablaVacia();
        } else if (data.status == 'error') {
            showToast(data.message, 'danger');
        }
    } catch (err) {
        console.group("❌ Error en la petición");
        console.error("🧠 Mensaje:", err.message);
        console.error("📚 Stack:", err.stack);
        console.groupEnd();
    }
}

const updateCita = async (idColumn, nameColumn, newValue) => {
    formData.append('idColumn', parseInt(idColumn) ?? '');
    formData.append('nameColumn', nameColumn ?? '');
    formData.append('newValue', newValue ?? '');

    await fetch('consultar/editCita', {
        method: 'POST',
        body: formData
    }).then(async res => {
        const contentType = res.headers.get("Content-Type") || "";
        const status = res.status;

        if (!res.ok || !contentType.includes("application/json")) {
            const htmlError = await res.text();

            console.group("🚨 Error de respuesta inesperada");
            console.log("📄 Content-Type:", contentType);
            console.log("📊 Status:", status);
            console.log("🧾 HTML recibido:\n", htmlError);
            console.groupEnd();

            throw new Error(`Respuesta inválida (${status})`);
        }

        return await res.json();
    }).then(data => {
        if (data.status == 'ok') {
            showToast(data.message, 'success');

            setTimeout(() => {
                window.location.reload();
            }, 1500);
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

const getData_search = async () => {
    formData.append('paciente', inputPatient.value.trim() ?? '');
    formData.append('medico', selectDoctor.value ?? '');
    formData.append('fecha', inputDate.value ?? '');
    formData.append('estado', selectStatus.value ?? '');

    await fetch('consultar/searchCita', {
        method: 'POST',
        body: formData
    }).then(async res => {
        const contentType = res.headers.get("Content-Type") || "";
        const status = res.status;

        if (!res.ok || !contentType.includes("application/json")) {
            const htmlError = await res.text();

            console.group("🚨 Error de respuesta inesperada");
            console.log("📄 Content-Type:", contentType);
            console.log("📊 Status:", status);
            console.log("🧾 HTML recibido:\n", htmlError);
            console.groupEnd();

            throw new Error(`Respuesta inválida (${status})`);
        }

        return await res.json();
    }).then(data => {
        if (data.status == 'ok') {
            renderCitas(data.message);
        } else if (data.status == 'error') {
            renderCitas('');
        }
    }).catch(err => {
        console.group("❌ Error en la petición");
        console.error("🧠 Mensaje:", err.message);
        console.error("📚 Stack:", err.stack);
        console.groupEnd();
    });
}
