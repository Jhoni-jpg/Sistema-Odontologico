<?php
require_once __DIR__ . '/../src/model/General.php';

$modelGeneral = new General();

$logCitas = $modelGeneral->obtenerLog_citas();

$countRows = $modelGeneral->contFilas();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>

<body>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OdontoSys - Inicio</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="stylesheet" href="/public/styles/inicio.css">
        <link rel="stylesheet" href="/public/styles/components/toast.css">
    </head>

    <body>

        <?php include __DIR__ . '/components/aside.php' ?>

        <div class="main-content">
            <div class="content">
                <!-- Welcome Header -->
                <div class="welcome-header">
                    <h1><i class="fa-solid fa-hand-wave"></i> Bienvenido a OdontoSys</h1>
                    <p>Sistema de Gestión de Citas Odontológicas</p>
                    <div class="date-time">
                        <i class="fa-solid fa-calendar"></i> <span id="currentDate"></span> |
                        <i class="fa-solid fa-clock"></i> <span id="currentTime"></span>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card primary">
                        <div class="stat-icon">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div class="stat-value"><?= $countRows['citas-hoy'] ?></div>
                        <div class="stat-label">Citas de Hoy</div>
                        <div class="stat-change positive">
                            <i class="fa-solid fa-arrow-up"></i> +12% vs ayer
                        </div>
                    </div>

                    <div class="stat-card success">
                        <div class="stat-icon">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="stat-value"><?= $countRows['pacientes'] ?></div>
                        <div class="stat-label">Pacientes Activos</div>
                        <div class="stat-change positive">
                            <i class="fa-solid fa-arrow-up"></i> +8% este mes
                        </div>
                    </div>

                    <div class="stat-card warning">
                        <div class="stat-icon">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="stat-value"><?= $countRows['citas'] ?></div>
                        <div class="stat-label">Citas Pendientes</div>
                        <div class="stat-change negative">
                            <i class="fa-solid fa-arrow-down"></i> -3 confirmadas
                        </div>
                    </div>

                    <div class="stat-card danger">
                        <div class="stat-icon">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="stat-value"><?= $countRows['medicosDisponibles'] ?></div>
                        <div class="stat-label">Médicos Disponibles</div>
                        <div class="stat-change positive">
                            <i class="fa-solid fa-check"></i> Todos activos
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h2><i class="fa-solid fa-bolt"></i> Acciones Rápidas</h2>
                    <div class="actions-grid">
                        <a href="asignar" class="action-card primary">
                            <i class="fa-solid fa-calendar-plus"></i>
                            <span>Nueva Cita</span>
                        </a>

                        <a href="consultar" class="action-card success">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span>Buscar Citas</span>
                        </a>

                        <a href="paciente" class="action-card warning">
                            <i class="fa-solid fa-user-plus"></i>
                            <span>Nuevo Paciente</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="recent-activity">
                    <h2><i class="fa-solid fa-history"></i> Actividad Reciente</h2>
                    <div class="activity-list">
                    </div>
                    <div class="containerButton__expanded">
                        <button class="logsExpanded">
                            Ver mas
                            <i class="fa-solid fa-circle-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <?php include __DIR__ . '/components/footer.php'  ?>
        <script src="/public/javascript/toast.js" defer></script>
        <script src="/public/javascript/AJAX/inicio.js" defer></script>
        <script src="/public/javascript/inicio.js" defer></script>
    </body>

    </html>