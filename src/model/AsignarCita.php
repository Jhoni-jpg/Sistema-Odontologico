<?php

require_once __DIR__ . '/../../others/config/ConfigDatabase.php';
require_once __DIR__ . '/../model/General.php';

class AsignarCita
{
    public $citnumero;
    public $citfecha;
    public $cithora;
    public $citpaciente;
    public $citmedico;
    public $citconsultorio;
    public $citmotivo;
    public $citobservaciones;
    public $citestado;
    public PDO $pdo;
    public General $modelGeneral;


    public function __construct()
    {
        $conexion = new BDConnect();
        $this->modelGeneral = new General();
        $this->pdo = $conexion->establecerConexion();
    }

    public function setNumero($numero)
    {
        $this->citnumero = $numero;
    }

    public function setFecha($fecha)
    {
        $this->citfecha = $fecha;
    }

    public function setHora($hora)
    {
        $this->cithora = $hora;
    }
    
    public function setPaciente($paciente)
    {
        $this->citpaciente = $paciente;
    }
    
    public function setMedico($medico) {
        $this->citmedico = $medico;
    }
    
    public function setConsultorio($consultorio)
    {
        $this->citconsultorio = $consultorio;
    }

    public function setMotivo($motivo) {
        $this->citmotivo = $motivo;
    }

    public function setObservaciones($observaciones) {
        $this->citobservaciones = $observaciones;
    }

    public function searchCita()
    {
        try {
            $sql = 'SELECT * FROM citas';
            $stmt = $this->pdo->prepare($sql);
            $capture = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($capture) {
                return [
                    'citnumero' => $capture['CitNumero'],
                    'citfecha' => $capture['CitFecha'],
                    'cithora' => $capture['CitHora'],
                    'citpaciente' => $capture['CitPaciente'],
                    'citmedico' => $capture['CitMedico'],
                    'citconsultorio' => $capture['CitConsultorio']
                ];
            }

            return [];
        } catch (PDOException $err) {
            echo "Ha ocurrido un error inesperado: $err";
            return [];
        }
    }

    public function consultarMedicos() {
        try {
            $sql = 'SELECT * FROM Medicos';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $capture = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $capture ?: [];
        } catch (PDOException $err) {
            echo "Error en la consulta de datos $err";
            return [];
        }
    }

    public function consultarConsultorios() {
        try {
            $sql = 'SELECT * FROM Consultorios';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $capture = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $capture ?: [];
        } catch (PDOException $err) {
            echo "Error en la consulta de datos $err";
            return [];
        }
    }
    
    public function horarioOcupado() {
        try {
            $sql = 'SELECT citpaciente, cithora FROM citas WHERE cithora = ? AND citfecha = ? AND citconsultorio = ? AND citpaciente = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $this->cithora,
                $this->citfecha,
                $this->citconsultorio,
                $this->citpaciente
            ]);
            $capture = $stmt->fetch();

            return $capture ?: '';
        } catch (PDOException $err) {
            echo "Error en la consulta de datos $err";
            return '';
        }
    }

    public function citaAsignada() {
        try {
            $sql = 'SELECT citpaciente, citmotivo FROM citas WHERE citmotivo = ? AND citpaciente = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $this->citmotivo,
                $this->citpaciente
            ]);
            $capture = $stmt->fetch();

            return $capture ?: '';
        } catch (PDOException $err) {
            echo "Error en la consulta de datos $err";
            return '';
        }
    }

    public function addCita()
    {
        try {
            $sql = 'INSERT INTO citas(citfecha, cithora, citpaciente, citmedico, citconsultorio, citmotivo, citobervaciones) VALUES (?, ?, ?, ?, ?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $this->citfecha,
                $this->cithora,
                $this->citpaciente,
                $this->citmedico,
                $this->citconsultorio,
                $this->citmotivo,
                $this->citobservaciones
            ]);

            $this->modelGeneral->registrarLog('INFO', 'addCita - model: AsignarCita', 'cita registrada', 'Registro realizado por: NONE',
            json_encode([
                'fechaCita' => $this->citfecha,
                'horaCita' => $this->cithora,
                'pacienteCita' => $this->citpaciente,
                'medicoCita' => $this->citmedico,
                'consultorioCita' => $this->citconsultorio,
                'motivoCita' => $this->citmotivo,
                'observacionesCita' => $this->citobservaciones
            ]));

            return true;
        } catch (PDOException $err) {
            echo "Error al agregar la cita $err";
            return false;
        }
    }
}
