
const inputPatient = document.getElementById('searchPatient')
const selectDoctor = document.getElementById('searchDoctor');
const inputDate = document.getElementById('searchDate');
const selectStatus = document.getElementById('searchStatus');

function capitalizarPalabras(texto) {
    return texto.split(" ").map(palabra => palabra.charAt(0).toUpperCase() + palabra.slice(1).toLowerCase()).join(" ");
}

function renderCitas(data) {
    const cuerpoTabla = document.getElementById('appointmentsBody');
    const estados = new Map();

    estados.set('asignada', 'confirmed');
    estados.set('cumplida', 'completed');
    estados.set('solicitada', 'pending');
    estados.set('cancelada', 'cancelled');

    cuerpoTabla.innerHTML = '';

    if (!data.length) {
        cuerpoTabla.innerHTML = `<tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <i class="fa-solid fa-calendar-xmark"></i>
                                            <h3>No se encontraron resultados</h3>
                                            <p>Intente ajustar los filtros de búsqueda</p>
                                        </div>
                                    </td>
                                </tr>`;
        return;
    }

    data.forEach((cita) => {
        const row = `<tr class="fila" data-id="${cita.numerocita}">
                                        <input id="inputCit_numero" type="hidden" value="${cita.numerocita}">
                                        <td><strong>#${String(cita.numerocita % 1000).padStart(3, "0")}</strong></td>
                                        <td>${cita.nombrepaciente} ${cita.apellidopaciente}</td>
                                        <td class="editable-cell" data-field="doctor">
                                            <span class="cell-display textCell">${cita.nombremedico} ${cita.apellidomedico}</span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="date">
                                            <span class="cell-display textCell">${cita.fechacita}</span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="time">
                                            <span class="cell-display textCell">${cita.horacita}</span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="office">
                                            <span class="cell-display textCell">Consultorio - ${cita.consultorio}</span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="reason">
                                            <span class="cell-display textCell">${capitalizarPalabras(cita.motivocita)}</span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="status">
                                            <span class="cell-display textCell status-badge ${estados.get(cita.estadocita) ?? ''}">${capitalizarPalabras(cita.estadocita)}</span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons normal-actions">
                                                <button class="btn btn-danger btn-sm buttonsTools-delete">
                                                    <i class="fa-solid fa-trash"></i> Eliminar
                                                    <input id='eliminarCita' type="hidden" value="${cita.numerocita}">
                                                </button>
                                            </div>
                                            <div class="action-buttons edit-actions hidden">
                                                <button class="btn btn-success btn-sm btn-saveData">
                                                    <i class="fa-solid fa-save"></i> Guardar
                                                    <input id='guardarCambios-id' type="hidden" value="${cita.numerocita}">
                                                </button>
                                                <button class="btn btn-danger btn-sm btn-cancelChanges">
                                                    <i class="fa-solid fa-times"></i> Cancelar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>`;

        cuerpoTabla.insertAdjacentHTML("beforeend", row);
    });
}

let cooldown;

document.addEventListener('DOMContentLoaded', () => {
    [inputPatient, selectDoctor, inputDate, selectStatus].forEach((event) => {
        event.addEventListener('input', (e) => {
            clearTimeout(cooldown);

            if (e.target.value.length < 3) return;

            cooldown = setTimeout(() => {
                console.log(e.target.value);
                getData_search
            }, 1000);
        });
        event.addEventListener('change', getData_search);
    });
});