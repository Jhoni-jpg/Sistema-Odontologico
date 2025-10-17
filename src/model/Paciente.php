<?php

require_once __DIR__ . '/../../others/config/ConfigDatabase.php';
require_once __DIR__ . '/../model/General.php';

class Paciente
{
    public $pacidentificacion;
    public $pacnombres;
    public $pacapellidos;
    public $pacfechanacimiento;
    public $pacsexo;
    public PDO $pdo;
    public General $modelGeneral;

    public function __construct()
    {
        $this->modelGeneral = new General();
        $conexion = new BDConnect();
        $this->pdo = $conexion->establecerConexion();
    }

    public function setIdentificacion($identificacion)
    {
        $this->pacidentificacion = $identificacion;
    }

    public function setNombres($nombres)
    {
        $this->pacnombres = $nombres;
    }

    public function setApellidos($apellidos)
    {
        $this->pacapellidos = $apellidos;
    }

    public function setFechanacimiento($nacimiento)
    {
        $this->pacfechanacimiento = $nacimiento;
    }

    public function setSexo($sexo)
    {
        $this->pacsexo = $sexo;
    }

    public function searchPatient()
    {
        try {
            $sql = 'SELECT * FROM pacientes';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $capture = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $capture ?: '';
        } catch (PDOException $err) {
            echo "Ha ocurrido un error inesperado: $err";
            return '';
        }
    }

    public function patientExists($identificacion = '')
    {
        try {
            $sql = 'SELECT pacidentificacion FROM pacientes WHERE pacidentificacion = ?';
            $stmt = $this->pdo->prepare($sql);

            if (empty($identificacion)) {
                $stmt->execute([$this->pacidentificacion]);
            } else {
                $stmt->execute([$identificacion]);
            }

            $capture = $stmt->fetch();

            if ($capture) {
                return true;
            }

            return false;
        } catch (PDOException $err) {
            echo "Ha ocurrido un error inesperado en la busqueda de credenciales: $err";
            return false;
        }
    }

    public function addPatient()
    {
        try {
            $sql = 'INSERT INTO pacientes(pacidentificacion, pacnombres, pacapellidos, pacfechanacimiento, pacsexo) VALUES (?, ?, ?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $this->pacidentificacion,
                $this->pacnombres,
                $this->pacapellidos,
                $this->pacfechanacimiento,
                $this->pacsexo
            ]);

            $identificacionPaciente = $this->pacidentificacion;

            $this->modelGeneral->registrarLog('INFO', 'addPatient - model: Paciente', 'paciente registrado', "Paciente registrado por: NONE - Paciente registrado: $identificacionPaciente",
            json_encode([
                'identificacionPaciente' => $identificacionPaciente,
                'nombresPaciente' => $this->pacnombres,
                'apellidosPaciente' => $this->pacapellidos,
                'fechanacimientoPaciente' => $this->pacfechanacimiento,
                'sexoPaciente' => $this->pacsexo
            ]));

            return true;
        } catch (PDOException $err) {
            echo "Error en la insercion de datos $err";
            error_log($err);
            return false;
        }
    }
}
