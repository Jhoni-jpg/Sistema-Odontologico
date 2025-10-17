let idFila = null;
let activeCell = null;
let isEditing = false;
let currentEditingCell = null;
let currentEditingRow = null;

async function buildSelectHTML(content, dataField) {
    try {
        const completeName = '';

        if (dataField === 'doctor') {
            const data = await fetchFunction_getMedics();

            if (Array.isArray(data)) {
                return `
            <select id="${dataField}Values" data-column="medico" class="opcionesDesplegadas form-select">
                ${data.map(val => {
                    const completeName = `${val.mednombres} ${val.medapellidos}`;
                    const isSelected = completeName === content ? "selected" : "";
                    let dniMedic = val.medidentificacion;
                    return `<option value="${dniMedic.trim()}" ${isSelected}>${completeName}</option>`;
                }).join("")}
            </select>
        `;
            }
        }


        if (dataField === 'date') {
            return `
                <input data-column='fecha' type='date' id='${dataField}Values' class='opcionesDesplegadas form-input' value='${content}'>
            `;
        }

        if (dataField === 'time') {
            return `
                <input data-column='hora' type='time' id='${dataField}Values' class='opcionesDesplegadas form-input' value='${content}'>
            `;
        }

        if (dataField === 'office') {
            const data = await fetchFunction_getConsultorios();
            const textFormat = parseInt(content.replace(/\D/g, ""));
            if (Array.isArray(data)) {
                return `
                    <select id='${dataField}Values' data-column='consultorio' class='opcionesDesplegadas form-select'>
                        ${data.map(val => `
                            <option value='${val.connumero}' 
                                ${val.connumero === textFormat ? "selected" : ""}>
                                ${val.connumero}
                            </option>
                        `).join("")}
                    </select>
                `;
            }
        }

        if (dataField === 'reason') {
            const listReason = [
                'Consulta General',
                'Limpieza Dental',
                'Revision De Ortodoncia',
                'Extraccion Dental',
                'Tratamiento De Endodoncia',
                'Colocacion De Implante',
                'Blanqueamiento Dental',
                'Urgencia Dental',
                'Control Postoperatorio',
                'Otro'
            ];

            return `
                <select id='${dataField}Values' data-column='motivo' class='opcionesDesplegadas form-select'>
                    ${listReason.map(val => `
                        <option value='${val}' ${val === content ? "selected" : ""}>${val}</option>
                    `).join("")}
                </select>
            `;
        }

        if (dataField === 'status') {
            const listStatus = ['Asignada', 'Cumplida', 'Solicitada', 'Cancelada'];
            return `
                <select id='${dataField}Values' data-column='estado' class='opcionesDesplegadas form-select'>
                    ${listStatus.map(val => `
                        <option value='${val}' ${val === content ? "selected" : ""}>${val}</option>
                    `).join("")}
                </select>
            `;
        }
    } catch (err) {
        console.error(`Ha ocurrido un error inesperado ${err}`);
    }

    return `
        <select id='Values' class='opcionesDesplegadas form-select'>
            <option value='Sin datos'>Sin datos</option>
        </select>
    `;
}

async function startEdit(celda) {
    if (activeCell && activeCell !== celda) restoreActive();

    if (!celda.dataset.original) celda.dataset.original = celda.innerHTML;

    const content = celda.textContent.trim();
    const html = await buildSelectHTML(content, celda.dataset.field);
    celda.innerHTML = html;

    const select = celda.querySelector('select') ??
        celda.querySelector('input[type="date"]') ??
        celda.querySelector('input[type="time"]');

    celda._currentSelect = select;
    select.focus();

    select.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') restoreActive();
        else if (e.key === 'Enter') commitActive();
    });

    activeCell = celda;
}

function commitActive() {
    if (!activeCell) return;

    const sel = activeCell._currentSelect ||
        activeCell.querySelector('select') ||
        activeCell.querySelector('input[type="date"]') ||
        activeCell.querySelector('input[type="time"]');

    const newVal = sel?.value;
    activeCell.innerHTML = newVal ?? activeCell.dataset.original ?? '';

    delete activeCell.dataset.original;
    delete activeCell._currentSelect;
    activeCell = null;
}

function restoreActive() {
    if (!activeCell) return;
    activeCell.innerHTML = activeCell.dataset.original ?? '';
    delete activeCell.dataset.original;
    delete activeCell._currentSelect;
    activeCell = null;
}

function clickEvent_table(row) {
    row.forEach(celda => {
        celda.addEventListener('click', () => {
            const currentCell = celda;
            const currentRow = celda.closest('tr');

            if (currentEditingCell === currentCell) return;

            if (currentEditingCell && currentEditingCell !== currentCell) {
                currentEditingCell.classList.remove('editionCell-active');
            }

            currentEditingCell = currentCell;
            currentCell.classList.add('editionCell-active');

            if (currentEditingRow === currentRow && activeCell === celda) return;

            if (currentEditingRow && currentEditingRow !== currentRow) {
                restoreButton(currentEditingRow);
                restoreActive();
                currentEditingRow.classList.remove('editing');
            }

            currentEditingRow = currentRow;
            currentRow.classList.add('editing');
            startEdit(celda);
            buttonsEdit(currentRow);
        });
    });
}

function buttonsEdit(row) {
    if (isEditing) return;
    isEditing = true;

    const buttons = row.querySelector('.edit-actions');
    const buttonsOptions = row.querySelector('.action-buttons');
    if (!buttons || !buttonsOptions) return;

    buttonsOptions.classList.remove('transitionFadeIn_button', 'hidden');
    buttonsOptions.classList.add('transitionFadeOut_button');

    buttonsOptions.addEventListener('animationend', () => {
        buttonsOptions.classList.add('hidden');
        buttonsOptions.classList.remove('transitionFadeOut_button');

        buttons.classList.remove('hidden', 'transitionFadeOut_button');
        buttons.classList.add('transitionFadeIn_button');
    }, { once: true });
}

function restoreButton(row) {
    if (!row) return;
    isEditing = false;

    const buttons = row.querySelector('.edit-actions');
    const buttonsOptions = row.querySelector('.action-buttons');
    if (!buttons || !buttonsOptions) return;

    buttons.classList.remove('transitionFadeIn_button', 'hidden');
    buttons.classList.add('transitionFadeOut_button');

    buttons.addEventListener('animationend', () => {
        buttons.classList.add('hidden');
        buttons.classList.remove('transitionFadeOut_button');

        buttonsOptions.classList.remove('hidden', 'transitionFadeOut_button');
        buttonsOptions.classList.add('transitionFadeIn_button');
    }, { once: true });
}

let cooldown_update = false;

function saveChanges_appointmente(buttonSave) {
    buttonSave.forEach((button) => {
        button.addEventListener('click', () => {
            if (cooldown_update) {
                return console.log('cooldown activo');
            }

            const inEdition_row = document.querySelector('.editing');

            if (!inEdition_row) return;

            const cellEdit = document.querySelector('.editionCell-active');

            if (!cellEdit) return;

            const elementEdit = cellEdit.querySelector('input, select');

            const fatherElement = button.closest('tr');

            if (elementEdit instanceof HTMLSelectElement) {
                if (!elementEdit) return;

                updateCita(fatherElement.dataset.id, elementEdit.dataset.column, elementEdit.value);
            }

            if (elementEdit instanceof HTMLInputElement) {
                if (!elementEdit) return;

                updateCita(fatherElement.dataset.id, elementEdit.dataset.column, elementEdit.value);
            }

            cooldown_update = true;
            setTimeout(() => {
                cooldown_update = false;
            }, 1500);
        });
    });
}


function cancelChanges(buttonCancel) {
    buttonCancel.forEach((value) => {
        value.addEventListener('click', () => {
            restoreActive();


            if (currentEditingCell) currentEditingCell.classList.remove('editionCell-active');

            if (currentEditingRow) restoreButton(currentEditingRow);


            currentEditingRow?.classList.remove('editing');
            currentEditingRow = null;
        });
    });
}

function deleteAppointment(container) {
    container.addEventListener('click', (e) => {
        if (e.target.closest('.buttonsTools-delete')) {
            const button = e.target.closest('.buttonsTools-delete');
            const idCita = button.dataset.id;

            const deleteConfirm = confirm(`¿Estás seguro de que deseas eliminar la cita #${idCita}?`);

            if (!deleteConfirm) return;

            fetchFunction_eliminarCita(idCita);
        }
    });
}

function tablaVacia() {
    const bodyTable = document.getElementById('appointmentsBody');

    if (!bodyTable) return;

    if (!bodyTable.children.length > 0) {
        bodyTable.innerHTML = `<tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <i class="fa-solid fa-calendar-xmark"></i>
                                            <h3>No se encontraron resultados</h3>
                                            <p>Intente ajustar los filtros de búsqueda</p>
                                        </div>
                                    </td>
                                </tr>`;
    }
}

document.addEventListener('click', (e) => {
    if (!activeCell) return;
    if (
        activeCell.contains(e.target) ||
        e.target.closest('.btn-saveData') ||
        e.target.closest('.btn-cancelChanges')
    ) return;

    if (currentEditingCell) currentEditingCell.classList.remove('editionCell-active');

    if (currentEditingRow) restoreButton(currentEditingRow);
    restoreActive();
    currentEditingRow?.classList.remove('editing');
    currentEditingRow = null;
});

document.addEventListener('DOMContentLoaded', () => {
    clickEvent_table(document.querySelectorAll('.editable-cell'));
    cancelChanges(document.querySelectorAll('.btn-cancelChanges'));
    saveChanges_appointmente(document.querySelectorAll('.btn-saveData'));
    deleteAppointment(document.getElementById('appointmentsBody'));
});