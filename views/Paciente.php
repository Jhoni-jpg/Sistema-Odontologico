<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes</title>
    <link rel="stylesheet" href="/public/styles/components/toast.css">
    <link rel="stylesheet" href="/public/styles/paciente.css">
</head>

<body>
    <?php include __DIR__ . '/components/aside.php' ?>

    <div class="main-content">
        <div class="content">
            <div class="form-card">
                <div class="form-header">
                    <i class="fa-solid fa-user-plus form-icon"></i>
                    <h2>Nuevo Paciente</h2>
                    <p class="subtitle">Complete la información del paciente para registrarlo en el sistema</p>
                </div>

                <form id="patientForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="pacidentificacion">
                                Número de Identificación <span class="required">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-id-card"></i>
                                <input type="text"
                                    id="pacidentificacion"
                                    name="identificacion"
                                    class="form-input"
                                    placeholder="Ej: 1234567890"
                                    required
                                    maxlength="20"
                                    pattern="[0-9]+"
                                    title="Solo se permiten números">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pacnombres">
                                Nombres <span class="required">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-user"></i>
                                <input type="text"
                                    id="pacnombres"
                                    name="nombres"
                                    class="form-input"
                                    placeholder="Ej: Juan Carlos"
                                    required
                                    maxlength="100"
                                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                    title="Solo se permiten letras y espacios">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pacapellidos">
                                Apellidos <span class="required">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-user"></i>
                                <input type="text"
                                    id="pacapellidos"
                                    name="apellidos"
                                    class="form-input"
                                    placeholder="Ej: Pérez García"
                                    required
                                    maxlength="100"
                                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                    title="Solo se permiten letras y espacios">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pacfechanacimiento">
                                Fecha de Nacimiento <span class="required">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="fa-solid fa-calendar"></i>
                                <input type="date"
                                    id="pacfechanacimiento"
                                    name="fechanacimiento"
                                    class="form-input"
                                    required
                                    max="">
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label>Sexo <span class="required">*</span></label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio"
                                        id="masculino"
                                        name="sexo"
                                        value="M"
                                        required>
                                    <label for="masculino">
                                        <i class="fa-solid fa-mars"></i> Masculino
                                    </label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio"
                                        id="femenino"
                                        name="sexo"
                                        value="F"
                                        required>
                                    <label for="femenino">
                                        <i class="fa-solid fa-venus"></i> Femenino
                                    </label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio"
                                        id="otro"
                                        name="sexo"
                                        value="O"
                                        required>
                                    <label for="otro">
                                        <i class="fa-solid fa-genderless"></i> Otro
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="buttons">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-save"></i>
                            Guardar Paciente
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fa-solid fa-eraser"></i>
                            Limpiar Formulario
                        </button>
                        <a href="consultar" class="btn btn-primary">
                            <i class="fa-solid fa-arrow-left"></i>
                            Volver a Consultar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/components/footer.php' ?>

    <script src="/public/javascript/toast.js"></script>
    <script src="/public/javascript/AJAX/Paciente.js" defer></script>
</body>

</html>