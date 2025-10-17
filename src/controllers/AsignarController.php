<?php
require_once __DIR__ . '/../model/AsignarCita.php';
require_once __DIR__ . '/../model/Paciente.php';

class AsignarController extends ControllerViews
{
    private AsignarCita $modelCita;
    private Paciente $modelPatient;

    public function __construct()
    {
        $this->modelCita = new AsignarCita();
        $this->modelPatient = new Paciente();
    }

    private function jsonResponse($status, $data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => $status,
            'message' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function newCita()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fecha = trim(filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
            $hora = trim(filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
            $paciente = trim(filter_input(INPUT_POST, 'paciente', FILTER_SANITIZE_NUMBER_INT) ?? '');
            $medico = trim(filter_input(INPUT_POST, 'medico', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
            $consultorio = trim(filter_input(INPUT_POST, 'consultorio' ?? ''));
            $motivo = strtolower(trim(filter_input(INPUT_POST, 'motivo')));
            $observaciones = trim(filter_input(INPUT_POST, 'observaciones'));

            $this->modelCita->setFecha($fecha);
            $this->modelCita->setHora($hora);
            $this->modelCita->setPaciente($paciente);
            $this->modelCita->setMedico($medico);
            $this->modelCita->setConsultorio($consultorio);
            $this->modelCita->setMotivo($motivo);
            $this->modelCita->setObservaciones($observaciones);

            $errors = [];

            if (empty($fecha)) {
                $errors[] = 'Campo "fecha" vacio';
            }

            if (empty($hora)) {
                $errors[] = 'Campo "hora" vacio';
            }

            if (empty($paciente)) {
                $errors[] = 'Campo "paciente" vacio"';
            }

            if (!preg_match('/^[0-9]+$/', $paciente)) {
                $errors[] = 'Campo de identificacion del paciente no valido';
            }

            if (strlen($paciente) > 10) {
                $errors[] = 'El numero de identificacion no puede superar los 10 caracteres';
            }

            if (strlen($paciente) < 10) {
                $errors[] = 'El numero de identificacion no puede ser menor de 10 caracteres';
            }

            if (empty($medico)) {
                $errors[] = 'Campo "medico" vacio';
            }

            if (empty($consultorio)) {
                $errors[] = 'Campo "consultorio" vacio';
            }

            if (empty($motivo)) {
                $errors[] = 'Campo "motivo" vacio';
            }

            if (empty($observaciones)) {
                $errors[] = 'Campo "observaciones" vacio';
            }

            if (!empty($errors)) {
                return $this->jsonResponse('error', $errors);
            }

            $patientExists = $this->modelPatient->patientExists($paciente);

            if (!$patientExists) {
                return $this->jsonResponse('error', 'La identificacion digitada no se encuentra asignada a un paciente');
            }

            $horarioApartado = $this->modelCita->horarioOcupado();

            if (!empty($horarioApartado)) {
                return $this->jsonResponse('error', 'Consultorio apartado en el horario y fecha establecido');
            }

            $citaAsignada = $this->modelCita->citaAsignada();

            if (!empty($citaAsignada)) {
                return $this->jsonResponse('error', 'Esta cita ya se encuentra asignada para este paciente');
            }

            $addPatient = $this->modelCita->addCita();

            if ($addPatient) {
                $this->modelCita->setFecha('');
                $this->modelCita->setHora('');
                $this->modelCita->setPaciente('');
                $this->modelCita->setMedico('');
                $this->modelCita->setConsultorio('');
                $this->modelCita->setMotivo('');
                $this->modelCita->setObservaciones('');
                return $this->jsonResponse('ok', 'Cita agregada correctamente', 201);
            }

            return $this->jsonResponse('error', 'Error al agregar la respectiva cita', 500);
        }

        return $this->jsonResponse('error', 'Metodo no permitido', 405);
    }

    public function getMedics()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data && $data['status'] == 'ok') {
                $dataCapture = $this->modelCita->consultarMedicos();

                if (is_array($dataCapture) && !empty($dataCapture)) {
                    return $this->jsonResponse('ok', $dataCapture);
                } else {
                    return $this->jsonResponse('error', 'Sin datos');
                }
            }
        }
    }

    public function getConsultorio()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data && $data['status'] == 'ok') {
                $dataCapture = $this->modelCita->consultarConsultorios();

                if (is_array($dataCapture) && !empty($dataCapture)) {
                    return $this->jsonResponse('ok', $dataCapture);
                } else {
                    return $this->jsonResponse('error', 'Sin datos');
                }
            }
        }
    }

    public function getPatient()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data && $data['status'] == 'ok') {
                $dataCapture = $this->modelPatient->searchPatient();

                if (is_array($dataCapture) && !empty($dataCapture)) {
                    return $this->jsonResponse('ok', $dataCapture);
                } else {
                    return $this->jsonResponse('error', 'Sin datos');
                }
            }
        }
    }

    public function index()
    {
        $this->views('asignar');
    }
}
