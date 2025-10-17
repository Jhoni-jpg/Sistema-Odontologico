<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Cita - OdontoSys</title>
    <link rel="stylesheet" href="/public/styles/asignar.css">
    
    <link rel="stylesheet" href="/public/styles/components/toast.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/components/aside.php' ?>

    <div class="main-content">
        <div class="content">
            <div class="form-card">
                <div class="form-header">
                    <i class="fa-solid fa-calendar-plus form-icon"></i>
                    <h2>Asignar Nueva Cita</h2>
                    <p class="subtitle">Complete la información para programar una nueva cita médica</p>
                </div>

                <form id="appointmentForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="documento_paciente">
                                <i class="fa-solid fa-id-card"></i>
                                Documento del Paciente <span class="required">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-search"></i>
                                <input type="text"
                                    id="documento_paciente"
                                    name="paciente"
                                    class="form-input"
                                    placeholder="Buscar por documento..."
                                    required
                                    maxlength="20"
                                    pattern="[0-9]+"
                                    title="Solo se permiten números">
                            </div>
                            <div class="search-results" id="patientResults"></div>
                            <div class="availability-indicator" id="patientInfo" style="display: none;">
                                <i class="fa-solid fa-user-check"></i>
                                <span id="patientName"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="medico">
                                <i class="fa-solid fa-user-doctor"></i>
                                Médico <span class="required">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-stethoscope"></i>
                                <select id="medico" name="medico" class="form-select" required>
                                </select>
                            </div>
                            <div class="availability-indicator available" id="doctorAvailability" style="display: none;">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Médico disponible</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fecha">
                                <i class="fa-solid fa-calendar-days"></i>
                                Fecha de la Cita <span class="required">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-calendar"></i>
                                <input type="date"
                                    id="fecha"
                                    name="fecha"
                                    class="form-input"
                                    required
                                    min="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="consultorio">
                                <i class="fa-solid fa-hospital"></i>
                                Consultorio <span class="required">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-door-open"></i>
                                <select id="consultorio" name="consultorio" class="form-select" required>
                                </select>
                            </div>
                            <div class="availability-indicator available" id="officeAvailability" style="display: none;">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Consultorio disponible</span>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label>
                                <i class="fa-solid fa-clock"></i>
                                Horarios Disponibles <span class="required">*</span>
                            </label>
                            <div class="time-slots" id="timeSlots">
                                <div class="time-slot">
                                    <input type="radio" id="time_08_00" name="hora" value="08:00" required>
                                    <label for="time_08_00">08:00 AM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_08_30" name="hora" value="08:30" required>
                                    <label for="time_08_30">08:30 AM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_09_00" name="hora" value="09:00" required>
                                    <label for="time_09_00">09:00 AM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_09_30" name="hora" value="09:30" required>
                                    <label for="time_09_30">09:30 AM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_10_00" name="hora" value="10:00" required>
                                    <label for="time_10_00">10:00 AM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_10_30" name="hora" value="10:30" required>
                                    <label for="time_10_30">10:30 AM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_11_00" name="hora" value="11:00" required>
                                    <label for="time_11_00">11:00 AM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_11_30" name="hora" value="11:30" required>
                                    <label for="time_11_30">11:30 AM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_14_00" name="hora" value="14:00" required>
                                    <label for="time_14_00">02:00 PM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_14_30" name="hora" value="14:30" required>
                                    <label for="time_14_30">02:30 PM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_15_00" name="hora" value="15:00" required>
                                    <label for="time_15_00">03:00 PM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_15_30" name="hora" value="15:30" required>
                                    <label for="time_15_30">03:30 PM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_16_00" name="hora" value="16:00" required>
                                    <label for="time_16_00">04:00 PM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_16_30" name="hora" value="16:30" required>
                                    <label for="time_16_30">04:30 PM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_17_00" name="hora" value="17:00" required>
                                    <label for="time_17_00">05:00 PM</label>
                                </div>
                                <div class="time-slot">
                                    <input type="radio" id="time_17_30" name="hora" value="17:30" required>
                                    <label for="time_17_30">05:30 PM</label>
                                </div>
                            </div>
                            <div class="availability-indicator limited" id="timeAvailability" style="display: none;">
                                <i class="fa-solid fa-clock"></i>
                                <span>Seleccione fecha y médico para ver horarios disponibles</span>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="motivo">
                                <i class="fa-solid fa-clipboard-list"></i>
                                Motivo de la Consulta <span class="required">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-comment-medical"></i>
                                <select id="motivo" name="motivo" class="form-select" required>
                                    <option value="">Seleccione el motivo</option>
                                    <option value="consulta general">Consulta General</option>
                                    <option value="limpieza dental">Limpieza Dental</option>
                                    <option value="revision de ortodoncia">Revisión de Ortodoncia</option>
                                    <option value="extraccion dental">Extracción Dental</option>
                                    <option value="tratamiento de endodoncia">Tratamiento de Endodoncia</option>
                                    <option value="colocacion de implante">Colocación de Implante</option>
                                    <option value="blanqueamiento dental">Blanqueamiento Dental</option>
                                    <option value="urgencia dental">Urgencia Dental</option>
                                    <option value="control postoperatorio">Control Postoperatorio</option>
                                    <option value="otro">Otro (especificar en observaciones)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="observaciones">
                                <i class="fa-solid fa-note-sticky"></i>
                                Observaciones
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-pen"></i>
                                <textarea id="observaciones"
                                    name="observaciones"
                                    class="form-input"
                                    placeholder="Información adicional sobre la cita..."
                                    rows="3"
                                    maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="buttons">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-calendar-check"></i>
                            Asignar Cita
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fa-solid fa-eraser"></i>
                            Limpiar Formulario
                        </button>
                        <a href="consultar.php" class="btn btn-primary">
                            <i class="fa-solid fa-arrow-left"></i>
                            Volver a Consultas
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include __DIR__ . '/components/footer.php' ?>

    <script src="/public/javascript/AJAX/listarMedicos.js" defer></script>
    <script src="/public/javascript/AJAX/listarConsultorios.js" defer></script>
    <script src="/public/javascript/toast.js" defer></script>
    <script src="/public/javascript/asignar.js" defer></script>
    <script src="/public/javascript/AJAX/Asignar.js" defer></script>
    <script src="/public/javascript/AJAX/listarPacientes.js" defer></script>
</body>

</html>