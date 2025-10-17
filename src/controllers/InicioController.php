<?php
require_once __DIR__ . '/../model/General.php';

class InicioController extends ControllerViews
{
    public General $modelGeneral;

    public function __construct()
    {
        $this->modelGeneral = new General();
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

    public function getLogs()
    {
        $logsObtenidos = $this->modelGeneral->obtenerLog_citas();

        if ($logsObtenidos) {
            return $this->jsonResponse('ok', $logsObtenidos);
        }

        return $this->jsonResponse('error', 'No se encontraron registros');
    }

    public function index()
    {
        $this->views('inicio');
    }
}
