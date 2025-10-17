const fetchFunction = async () => {
    await fetch('asignar/getMedics', {
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
            addElements_listMedics(data.message);
        } else if (data.status == 'error') {
            addElements_listMedics(data.message);
            showToast('Ha ocurrido un error inesperado en el listado de medicos', 'danger')
        }
    }).catch(err => {
        console.group("❌ Error en la petición");
        console.error("🧠 Mensaje:", err.message);
        console.error("📚 Stack:", err.stack);
        console.groupEnd();
    });
}

function addElements_listMedics(medicos) {
    const selectBox = document.getElementById('medico');
    let tipoDr = '';

    selectBox.replaceChildren();

    if (Array.isArray(medicos)) {
        medicos.forEach(element => {
            const elementChild = document.createElement('option');
            selectBox.appendChild(elementChild);
            elementChild.className = 'listMedicos';
            elementChild.setAttribute('value', `${element.medidentificacion}`);

            element.medsexo == 'F' ? tipoDr = 'Dra' : tipoDr = 'Dr';

            elementChild.textContent = `${tipoDr}. ${element.mednombres} ${element.medapellidos} - ${element.medarea}`;
        });
    } else {
        const elementChild = document.createElement('option');
        selectBox.appendChild(elementChild);
        elementChild.className = 'listMedicos';
        elementChild.setAttribute('value', '');

        elementChild.textContent = medicos;
    }
}

function clickEvent_list() {
    document.addEventListener('DOMContentLoaded', () => {
        let buttonPressed = false;

        document.getElementById('medico').addEventListener('click', async () => {
            if (!buttonPressed) {
                await fetchFunction();

                buttonPressed = true;

                setTimeout(() => {
                    buttonPressed = false;
                }, 10000);
            } else {
                console.log('cooldown activo')
            }
        });
    });
}

clickEvent_list();