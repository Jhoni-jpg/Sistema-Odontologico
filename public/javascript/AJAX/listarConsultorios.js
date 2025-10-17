const fetchFunction_consultorios = async () => {
    await fetch('asignar/getConsultorio', {
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
            addElements_listConsultorio(data.message);
            console.log(data.message);
        } else if (data.status == 'error') {
            addElements_listConsultorio(data.message);
            showToast('Ha ocurrido un error inesperado en el listado de consultorios', 'danger')
        }
    }).catch(err => {
        console.group("❌ Error en la petición");
        console.error("🧠 Mensaje:", err.message);
        console.error("📚 Stack:", err.stack);
        console.groupEnd();
    });
}

function addElements_listConsultorio(consultorios) {
    const selectBox = document.getElementById('consultorio');

    selectBox.replaceChildren();

    if (Array.isArray(consultorios)) {

        consultorios.forEach(element => {
            const elementChild = document.createElement('option');
            selectBox.appendChild(elementChild);
            elementChild.className = 'listConsultorios';
            elementChild.setAttribute('value', `${element.connumero}`);

            elementChild.textContent = `${element.connombre} - ${element.condetalle}`;
        });
    } else {
        const elementChild = document.createElement('option');
            selectBox.appendChild(elementChild);
            elementChild.className = 'listConsultorios';
            elementChild.setAttribute('value', {});

            elementChild.textContent = consultorios;
    }

}


function add_listConsultorio(consultorios) {
    const selectBox = document.getElementById('edit_doctor');

    selectBox.replaceChildren();

    if (Array.isArray(consultorios)) {

        consultorios.forEach(element => {
            const elementChild = document.createElement('option');
            selectBox.appendChild(elementChild);
            elementChild.className = 'listConsultorios';
            elementChild.setAttribute('value', `${element.connumero}`);

            elementChild.textContent = `${element.connombre} - ${element.condetalle}`;
        });
    } else {
        const elementChild = document.createElement('option');
            selectBox.appendChild(elementChild);
            elementChild.className = 'listConsultorios';
            elementChild.setAttribute('value', {});

            elementChild.textContent = consultorios;
    }

}

function clickEvent_list() {
    document.addEventListener('DOMContentLoaded', () => {
        let buttonPressed = false;

        document.getElementById('consultorio').addEventListener('click', async () => {
            if (!buttonPressed) {
                await fetchFunction_consultorios();

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