<?php
require_once __DIR__ . '/../../others/config/ConfigDatabase.php';

class General
{
    public PDO $pdo;

    public function __construct()
    {
        $conexion = new BDConnect();
        $this->pdo = $conexion->establecerConexion();
    }

    public function contFilas()
    {
        try {
            $sql = 'SELECT countRows()';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $capture = $stmt->fetchColumn();

            if ($capture) {
                return json_decode($capture, true);
            }

            return [];
        } catch (PDOException $err) {
            echo "Ha ocurrido un error inesperado $err";
            return [];
        }
    }

    public function registrarLog($tipo, $origen, $titulo, $mensaje, $datos)
    {
        try {
            $sql = 'CALL registrar_log(?, ?, ?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                strtolower($tipo),
                $origen,
                $titulo,
                $mensaje,
                $datos
            ]);

            return true;
        } catch (PDOException $err) {
            echo "Ha ocurrido un error inesperado al ejecutar el log $err";
            error_log($err);
            return false;
        }
    }

    public function obtenerLog_citas() {
        try {
            $sql = "SELECT tiempo_dinamico(fecha) AS fecha_log, tipo, titulo, mensaje, datos FROM logs_app ORDER BY fecha DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $capture = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $capture ?: [];
        } catch (PDOException $err) {
            echo "Ha ocurrido un error inesperado en la consulta de datos $err";
            return [];
        }
    }
}
