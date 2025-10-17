async function getAll_Logs() {
    try {
        const res = await fetch('inicio/getLogs');

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