<?php
require_once __DIR__ . '/../src/model/Cita.php';
require_once __DIR__ . '/../src/model/AsignarCita.php';

$modelCita = new Cita();
$modelMedicos = new AsignarCita();

$getMedicos = $modelMedicos->consultarMedicos();
$getCitas = $modelCita->searchCitas();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Citas - OdontoSys</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/styles/consultar.css">
    <link rel="stylesheet" href="/public/styles/components/toast.css">
    <link rel="stylesheet" href="/public/styles/animations/consultar.css">
</head>

<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </button>

    <?php include __DIR__ . '/components/aside.php' ?>

    <div class="main-content">
        <div class="content">
            <div class="page-header">
                <h1><i class="fa-solid fa-magnifying-glass"></i> Consultar Citas</h1>
                <p>Busque, visualice y edite las citas directamente en la tabla</p>
            </div>

            <div class="search-section">
                <form class="search-form" id="searchForm">
                    <div class="form-group">
                        <label for="searchPatient">Paciente</label>
                        <div class="input-icon">
                            <i class="fa-solid fa-user"></i>
                            <input data-typeSarch="paciente" type="text" id="searchPatient" class="metodoBusqueda form-input" placeholder="Buscar por nombre o documento">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="searchDoctor">Médico</label>
                        <div class="input-icon">
                            <i class="fa-solid fa-user-doctor"></i>
                            <select data-typeSarch="medico" id="searchDoctor" class="metodoBusqueda form-select">
                                <option value="">Todos los médicos</option>
                                <?php if (!empty($getMedicos)): ?>
                                    <?php foreach ($getMedicos as $medicos): ?>
                                        <option value="<?= trim(htmlspecialchars($medicos['medidentificacion'])) ?>"><?= $medicos['mednombres'] ?> <?= $medicos['medapellidos'] ?> </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="searchDate">Fecha</label>
                        <div class="input-icon">
                            <i class="fa-solid fa-calendar"></i>
                            <input data-typeSarch="date" type="date" id="searchDate" value="" class="metodoBusqueda form-input">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="searchStatus">Estado</label>
                        <div class="input-icon">
                            <i class="fa-solid fa-filter"></i>
                            <select data-typeSarch="estado" id="searchStatus" class="metodoBusqueda form-select">
                                <option value="">Todos los estados</option>
                                <option value="asignada">Asignada</option>
                                <option value="cumplida">Cumplida</option>
                                <option value="solicitada">Solicitada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="appointments-container">
                <div class="table-responsive">
                    <table class="appointments-table" id="appointmentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paciente</th>
                                <th>Médico</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Consultorio</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="appointmentsBody">
                            <?php if (!empty($getCitas)): ?>
                                <?php foreach ($getCitas as $citaInfo): ?>
                                    <?php $nombrePaciente = $modelCita->obtenerNombre_paciente($citaInfo['citpaciente']); ?>
                                    <?php $nombreMedico = $modelCita->obtenerNombre_medico($citaInfo['citmedico']); ?>
                                    <tr class="fila" data-id="<?= $citaInfo['citnumero'] ?>">
                                        <input id="inputCit_numero" type="hidden" value="<?= $citaInfo['citnumero'] ?>">
                                        <input id="inputCit_obeservaciones" type="hidden" value="<?php $citaInfo['citobervaciones'] ?>">
                                        <td><strong>#<?= str_pad($citaInfo['citnumero'] % 1000, 3, "0", STR_PAD_LEFT) ?></strong></td>
                                        <td><?= ucwords(htmlspecialchars($nombrePaciente)) ?></td>
                                        <td class="editable-cell" data-field="doctor">
                                            <span class="cell-display textCell"><?= ucwords(htmlspecialchars($nombreMedico['mednombres'])) ?> <?= ucwords(htmlspecialchars($nombreMedico['medapellidos'])) ?> </span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="date">
                                            <span class="cell-display textCell"><?= $citaInfo['citfecha'] ?></span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="time">
                                            <span class="cell-display textCell"><?= $citaInfo['cithora'] ?></span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="office">
                                            <span class="cell-display textCell">Consultorio - <?= $citaInfo['citconsultorio'] ?></span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="reason">
                                            <span class="cell-display textCell"><?= ucwords($citaInfo['citmotivo']) ?></span>
                                            <div class="hover-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="editable-cell" data-field="status">
                                            <span class="cell-display textCell status-badge <?php
                                                                                            if ($citaInfo['citestado'] === 'asignada') {
                                                                                                echo 'confirmed';
                                                                                            }
                                                                                            if ($citaInfo['citestado'] === 'cumplida') {
                                                                                                echo 'completed';
                                                                                            }
                                                                                            if ($citaInfo['citestado'] === 'solicitada') {
                                                                                                echo 'pending';
                                                                                            }
                                                                                            if ($citaInfo['citestado'] === 'cancelada') {
                                                                                                echo 'cancelled';
                                                                                            }
                                                                                            ?>"><?= ucfirst($citaInfo['citestado']) ?></span>
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
                                                <button class="btn btn-danger btn-sm buttonsTools-delete" data-id="<?= $citaInfo['citnumero'] ?>">
                                                    <i class="fa-solid fa-trash"></i> Eliminar
                                                </button>
                                            </div>
                                            <div class="action-buttons edit-actions hidden">
                                                <button class="btn btn-success btn-sm btn-saveData">
                                                    <i class="fa-solid fa-save"></i> Guardar
                                                    <input id='guardarCambios-id' type="hidden" value="<?= $citaInfo['citnumero'] ?>">
                                                </button>
                                                <button class="btn btn-danger btn-sm btn-cancelChanges">
                                                    <i class="fa-solid fa-times"></i> Cancelar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <i class="fa-solid fa-calendar-xmark"></i>
                                            <h3>No se encontraron citas</h3>
                                            <p>Intente ajustar los filtros de búsqueda</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <?php include __DIR__ . '/components/footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/javascript/AJAX/consultar.js" defer></script>
    <script src="/public/javascript/consultarBusqueda.js"></script>
    <script src="/public/javascript/consultarEdicion.js" defer></script>
    <script src="/public/javascript/toast.js" defer></script>
</body>

</html>