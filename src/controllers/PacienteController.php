<?php
require_once __DIR__ . '/../model/Paciente.php';


class PacienteController extends ControllerViews
{
    private Paciente $modelPatient;

    public function __construct()
    {
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

    public function newPatient()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identificacion = trim(filter_input(INPUT_POST, 'identificacion', FILTER_SANITIZE_NUMBER_INT) ?? '');
            $nombres = $nombres  = trim(filter_input(INPUT_POST, 'nombres', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
            $apellidos = trim(filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
            $fechanacimiento = trim(filter_input(INPUT_POST, 'fechanacimiento') ?? '');
            $sexo = trim(filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

            $this->modelPatient->setIdentificacion($identificacion);
            $this->modelPatient->setNombres($nombres);
            $this->modelPatient->setApellidos($apellidos);
            $this->modelPatient->setFechanacimiento($fechanacimiento);
            $this->modelPatient->setSexo($sexo);

            $errors = [];

            if (empty($identificacion)) {
                $errors[] = 'Campo "identificacion" vacio';
            }

            if (!preg_match('/^[0-9]+$/', $identificacion)) {
                $errors[] = 'Campo de identificacion no valido';
            }

            if (strlen($identificacion) > 10) {
                $errors[] = 'El numero de identificacion no puede superar los 10 caracteres';
            }

            if (strlen($identificacion) < 10) {
                $errors[] = 'El numero de identificacion no puede ser menor de 10 caracteres';
            }

            if (empty($nombres)) {
                $errors[] = 'Campo "nombres" vacio';
            }

            if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombres)) {
                $errors[] = 'Asegurate que los nombres digitados no contengan algun tipo de caracter especial o numeros';
            }

            if (empty($apellidos)) {
                $errors[] = 'Campo "apellidos" vacio"';
            }

            if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $apellidos)) {
                $errors[] = 'Asegurate que los apellidos digitados no contengan algun tipo de caracter especial o numeros';
            }

            if (empty($fechanacimiento)) {
                $errors[] = 'Campo "fecha" vacio';
            }

            if (empty($sexo)) {
                $errors[] = 'Campo "sexo" vacio';
            }

            if (!empty($errors)) {
                return $this->jsonResponse('error', $errors, 200);
            }

            $patientExists = $this->modelPatient->patientExists();

            if ($patientExists) {
                return $this->jsonResponse('error', 'Paciente ya existente');
            }

            $addPatient = $this->modelPatient->addPatient();

            if ($addPatient) {
                $this->modelPatient->setIdentificacion('');
                $this->modelPatient->setNombres('');
                $this->modelPatient->setApellidos('');
                $this->modelPatient->setFechanacimiento('');
                $this->modelPatient->setSexo('');
                return $this->jsonResponse('ok', "Paciente agregado correctamente", 201);
            }

            return $this->jsonResponse('error', 'Error al agregar el paciente', 500);
        }

        return $this->jsonResponse('error', 'Metodo no permitido', 405);
    }

    public function index()
    {
        $this->views('paciente');
    }
}
