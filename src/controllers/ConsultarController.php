<?php
require_once __DIR__ . '/../model/Cita.php';
require_once __DIR__ . '/../model/Medico.php';
require_once __DIR__ . '/../model/Consultorio.php';

class ConsultarController extends ControllerViews
{
    public Cita $modelCita;
    public Medico $modelMedico;
    public Consultorio $modelConsultorio;

    public function __construct()
    {
        header('Access-Control-Allow-Origin: http://localhost');
        header('Access-Control-Allow-Credentials: true');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Allow-Methods: POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type');
            http_response_code(200);
            exit;
        }

        $this->modelMedico = new Medico();
        $this->modelCita = new Cita();
        $this->modelConsultorio = new Consultorio();
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

    public function editCita()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim(filter_input(INPUT_POST, 'idColumn', FILTER_SANITIZE_NUMBER_INT) ?? '');
            $column = trim(filter_input(INPUT_POST, 'nameColumn', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
            $newValue = trim(filter_input(INPUT_POST, 'newValue', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

            if (empty($id) && empty($column) && empty($newValue)) {
                return;
            }

            $this->modelCita->setNumero($id);

            if (!$this->modelCita->citaExistente()) {
                return $this->jsonResponse('error', 'La cita no se encuentra disponible');
            }

            $edicionCompletada = $this->completeEdition($id, $column, $newValue);

            if (!$edicionCompletada) {
                return $this->jsonResponse('error', 'Ha ocurrido un error inesperado en la edicion de campos');
            }

            return $this->jsonResponse('ok', 'Campo "' . ucfirst($edicionCompletada) . '" actualizado');
        }
    }

    public function completeEdition($id, $column, $value)
    {
        try {
            $this->modelCita->setNumero($id);

            if ($column == 'medico') {
                if (!$this->modelMedico->medicoExistente($value)) {
                    return $this->jsonResponse('error', 'El medico seleccionado no se encuentra registrado');
                }

                $campoMedico = $this->modelCita->actualizarCita($id, 'citmedico', $value);

                if (!$campoMedico) {
                    return $this->jsonResponse('error', 'No se ha podido actualizar el campo solicitado correctamente');
                }

                return 'medico';
            }

            if ($column == 'fecha') {
                $horarios = $this->modelCita->obtenerHorarios_cita();

                if (!$horarios) return;

                $this->modelCita->setFecha($value);
                $this->modelCita->setHora($horarios['cithora']);
                $this->modelCita->setConsultorio($horarios['citconsultorio']);

                $horarioOcupado = $this->modelCita->fechaOcupada();

                if ($horarioOcupado) {
                    return $this->jsonResponse('error', 'La fecha solicitada ya se encuentra ocupada');
                }

                $campoFecha = $this->modelCita->actualizarCita($id, 'citfecha', $value);

                if (!$campoFecha) {
                    return $this->jsonResponse('error', 'No se ha podido actualizar el campo solicitado correctamente');
                }

                return 'fecha';
            }

            if ($column == 'hora') {
                $horarios = $this->modelCita->obtenerHorarios_cita();

                if (!$horarios) return;

                $this->modelCita->setFecha($horarios['citfecha']);
                $this->modelCita->setHora($value);
                $this->modelCita->setConsultorio($horarios['citconsultorio']);

                $horarioOcupado = $this->modelCita->fechaOcupada();

                if ($horarioOcupado) {
                    return $this->jsonResponse('error', 'La hora solicitada ya se encuentra ocupada');
                }

                $campoHora = $this->modelCita->actualizarCita($id, 'cithora', $value);

                if (!$campoHora) {
                    return $this->jsonResponse('error', 'No se ha podido actualizar el campo solicitado correctamente');
                }

                return 'hora';
            }

            if ($column == 'consultorio') {
                $this->modelConsultorio->setNumero($value);
                $consultorioExistente = $this->modelConsultorio->consultorioExistente();

                if ($consultorioExistente) return $this->jsonResponse('error', 'Consultorio inexistente');

                $horarios = $this->modelCita->obtenerHorarios_cita();

                $this->modelCita->setFecha($horarios['citfecha']);
                $this->modelCita->setHora($horarios['cithora']);
                $this->modelCita->setConsultorio($value);

                $horarioOcupado = $this->modelCita->fechaOcupada();

                if ($horarioOcupado) {
                    return $this->jsonResponse('error', 'El consultorio solicitado ya se encuentra ocupado');
                }

                $campoConsultorio = $this->modelCita->actualizarCita($id, 'citconsultorio', $value);

                if (!$campoConsultorio) {
                    return $this->jsonResponse('error', 'No se ha podido actualizar el campo solicitado correctamente');
                }

                return 'consultorio';
            }

            if ($column == 'motivo') {
                $campoMotivo = $this->modelCita->actualizarCita($id, 'citmotivo', strtolower($value));

                if (!$campoMotivo) {
                    return $this->jsonResponse('error', 'No se ha podido actualizar el campo solicitado correctamente');
                }

                return 'motivo';
            }

            if ($column == 'estado') {
                $campoEstado = $this->modelCita->actualizarCita($id, 'citestado', strtolower($value));

                if (!$campoEstado) {
                    return $this->jsonResponse('error', 'No se ha podido actualizar el campo solicitado correctamente');
                }

                return 'estado';
            }

            return $this->jsonResponse('error', 'El campo solicitado no se encuentra disponible');
        } catch (Exception $err) {
            echo $err;
            return '';
        }
    }

    public function searchCita() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paciente = trim(filter_input(INPUT_POST, 'paciente', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
            $medico = trim(filter_input(INPUT_POST, 'medico', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
            $fecha = trim(filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
            $estado = trim(filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

            $busqueda = $this->modelCita->searchCitas_partition($paciente, $medico, $fecha, $estado);

            if (!$busqueda) {
                return $this->jsonResponse('error', $busqueda);
            }

            return $this->jsonResponse('ok', $busqueda);
        }
    }

    public function getCitas()
    {
        $citas = $this->modelCita->searchCitas();

        if (!empty($citas)) {
            return $this->jsonResponse('ok', $citas);
        } else {
            return $this->jsonResponse('error', 'Error en la obtencion de datos');
        }
    }

    public function eliminarCita()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim(filter_input(INPUT_POST, 'idCita', FILTER_SANITIZE_NUMBER_INT) ?? '');
            $errors = [];

            if (empty($id)) {
                return;
            }

            $this->modelCita->setNumero($id);

            $citaExistente = $this->modelCita->citaExistente();

            if (!$citaExistente) {
                $errors[] = 'Cita inexistente';
            }

            if (!empty($errors)) {
                return $this->jsonResponse('error', $errors);
            }

            $citaEliminada = $this->modelCita->deleteCitas();

            if ($citaEliminada) {
                return $this->jsonResponse('ok', 'Se ha eliminado la cita correctamente');
            }
        }
    }

    public function index()
    {
        $this->views('consultar');
    }
}
