<?php
    require_once __DIR__ . '/../../others/config/ConfigDatabase.php';
    require_once __DIR__ . '/../model/General.php';

    class Cita
    {
        public PDO $pdo;
        public BDConnect $connect;
        public General $modelGeneral;
        public $citnumero;
        public $citfecha;
        public $cithora;
        public $citpaciente;
        public $citmedico;
        public $citconsultorio;
        public $citestado;

        public function __construct()
        {
            $this->connect = new BDConnect();
            $this->modelGeneral = new General();
            $this->pdo = $this->connect->establecerConexion();
        }

        public function setNumero($numero)
        {
            $this->citnumero = $numero;
        }

        public function setFecha($ficha)
        {
            $this->citfecha = $ficha;
        }

        public function setHora($hora)
        {
            $this->cithora = $hora;
        }

        public function setPaciente($paciente)
        {
            $this->citpaciente = $paciente;
        }

        public function setMedico($medico)
        {
            $this->citmedico = $medico;
        }

        public function setConsultorio($consultorio)
        {
            $this->citconsultorio = $consultorio;
        }

        public function seetEstado($estado)
        {
            $this->citestado = $estado;
        }

        public function obtenerNumero()
        {
            return $this->citnumero;
        }

        public function obtenerFecha()
        {
            return $this->citfecha;
        }

        public function obtenerHora()
        {
            return $this->cithora;
        }

        public function obtenerPaciente()
        {
            return $this->citpaciente;
        }

        public function obtenerMedico()
        {
            return $this->citmedico;
        }

        public function obtenerConsultorio()
        {
            return $this->citconsultorio;
        }

        public function obtenerEstado()
        {
            return $this->citestado;
        }

        public function searchCitas()
        {
            try {
                $sql = 'SELECT * FROM citas ORDER BY CitNumero ASC ';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $capture = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $capture ?: [];
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado: $err";
                return [];
            }
        }

        public function searchCitas_partition($paciente, $medico, $fecha, $estado)
        {
            try {
                $sql = 'SELECT * FROM searchCita(?, ?, ?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $paciente,
                    $medico,
                    $fecha,
                    $estado
                ]);

                $capture = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $capture ?: [];
            } catch (PDOException $err) {
                error_log($err);
                echo "Ha ocurrido un error inesperado en la consulta de datos $err";
                return [];
            }
        }

        public function obtenerNombre_paciente($identificacion = '')
        {
            try {
                $sql =  'SELECT pacnombres, pacapellidos FROM pacientes WHERE pacidentificacion = ?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $identificacion ?: $this->citpaciente
                ]);
                $capture = $stmt->fetchColumn();

                return $capture ?: [];
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado en la consulta de datos $err";
                return [];
            }
        }

        public function obtenerNombre_medico($identificacion = '')
        {
            try {
                $sql =  'SELECT mednombres, medapellidos FROM medicos WHERE medidentificacion = ?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $identificacion ?: $this->citmedico
                ]);
                $capture = $stmt->fetch(PDO::FETCH_ASSOC);

                return $capture ?: [];
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado en la consulta de datos $err";
                return [];
            }
        }

        public function agregarCita()
        {
            try {
                $fecha = $this->citfecha;
                $hora = $this->cithora;
                $paciente = $this->citpaciente;
                $medico = $this->citmedico;
                $consultorio = $this->citconsultorio;
                $estado = $this->citestado;

                $sql = "INSERT INTO Citas(citfecha, cithora, citpaciente, citmedico, citconsultorio, citestado) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$fecha, $hora, $paciente, $medico, $consultorio, $estado]);

                return true;
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado en la insercion de citas $err";
                return false;
            }
        }

        public function citaExistente()
        {
            try {
                $sql = 'SELECT CitNumero FROM Citas WHERE CitNumero = ?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $this->citnumero
                ]);

                $capture = $stmt->fetch();

                return $capture ?: false;
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado en la consulta de datos $err";
                return false;
            }
        }

        public function deleteCitas()
        {
            try {
                $sql = 'DELETE FROM Citas WHERE CitNumero = ?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$this->citnumero]);

                $citaNumero = $this->citnumero;

                $this->modelGeneral->registrarLog(
                    'ERR',
                    'deleteCitas - model: Cita',
                    'cita eliminada',
                    "Cita eliminada por: NONE - Cita eliminada: $citaNumero",
                    '{}'
                );
                return true;
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado: $err";
                return false;
            }
        }

        public function actualizarCita($id, $column, $value)
        {
            try {
                $columnasPermitidas = ['citfecha', 'cithora', 'citmedico', 'citconsultorio', 'citmotivo', 'citestado'];

                if (!in_array($column, $columnasPermitidas)) {
                    throw new Exception('Columna no permitida');
                }

                $sql = "UPDATE Citas SET $column = ? WHERE citnumero = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $value,
                    $id
                ]);

                $formatColumn = str_replace('cit', '', $column);
                $formatFirst_letterUp = ucfirst($formatColumn);

                $this->modelGeneral->registrarLog(
                    'WAR',
                    'actualizarCita - model: Cita',
                    'cita actualizada',
                    "Cita actualizada por: NONE - Cita actualizada: $id - Campo actualizado: $formatFirst_letterUp - Nuevo valor: $value",
                    '{}'
                );

                return true;
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado en la actualizacion de campos $err";
                error_log($err);
                return false;
            }
        }

        public function getIdentification_paciente()
        {
            try {
                $sql = 'SELECT citpaciente FROM citas c INNER JOIN pacientes p ON c.citpaciente = p.pacidentificacion WHERE citnumero = ?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $this->obtenerNumero()
                ]);
                $capture = $stmt->fetchColumn();

                return $capture ?: '';
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado en la actualizacion de campos $err";
                error_log($err);
                return '';
            }
        }

        public function fechaOcupada($fecha = '', $hora = '', $consultorio = '')
        {
            try {
                $sql = 'SELECT cithora FROM citas WHERE cithora = ? AND citfecha = ? AND citconsultorio = ?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $fecha ?: $this->obtenerHora(),
                    $hora ?: $this->obtenerFecha(),
                    $consultorio ?: $this->obtenerConsultorio()
                ]);

                $capture = $stmt->fetch();

                return $capture ?: false;
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado en la consulta de datos $err";
                error_log($err);
                return false;
            }
        }

        public function obtenerHorarios_cita()
        {
            try {
                $sql = 'SELECT cithora, citfecha, citconsultorio FROM citas WHERE citnumero = ?';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $this->obtenerNumero()
                ]);

                $capture = $stmt->fetch(PDO::FETCH_ASSOC);

                return $capture ?: [];
            } catch (PDOException $err) {
                echo "Ha ocurrido un error inesperado en la consulta de datos $err";
                error_log($err);
                return [];
            }
        }
    }
