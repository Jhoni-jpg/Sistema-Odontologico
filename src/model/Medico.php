<?php

require_once __DIR__ . '/../../others/config/ConfigDatabase.php';

class Medico
{
    public $medidentificacion;
    public $mednombres;
    public $medapellidos;
    public $medsexo;
    public $medarea;
    public PDO $pdo;

    public function __construct()
    {
        $conexion = new BDConnect();
        $this->pdo = $conexion->establecerConexion();
    }

    public function setIdentificacion($identificacion)
    {
        $this->medidentificacion = $identificacion;
    }

    public function setNombres($nombres)
    {
        $this->mednombres = $nombres;
    }

    public function setApellidos($apellidos)
    {
        $this->medapellidos = $apellidos;
    }

    public function setSexo($sexo)
    {
        $this->medsexo = $sexo;
    }

    public function setArea($medarea)
    {
        $this->medarea = $medarea;
    }

    public function getIdentificacion()
    {
        return $this->medidentificacion;
    }

    public function getNombres()
    {
        return $this->mednombres;
    }

    public function getApellidos()
    {
        return $this->medapellidos;
    }

    public function getSexo()
    {
        return $this->medsexo;
    }

    public function getArea()
    {
        return $this->medarea;
    }

    public function medicoExistente($identificacion = '')
    {
        try {
            $sql = 'SELECT medidentificacion FROM medicos WHERE medidentificacion = ?';
            $stmt = $this->pdo->prepare($sql);

            if (empty($identificacion)) {
                $stmt->execute([$this->getIdentificacion()]);
            } else {
                $stmt->execute([$identificacion]);
            }

            $capture = $stmt->fetch();

            return $capture ?: false;
        } catch (PDOException $err) {
            echo "Ha ocurrido un error inesperado en la busqueda de credenciales: $err";
            return false;
        }
    }


    public function medicoArea()
    {
        try {
            $sql = 'SELECT medarea FROM medicos WHERE medidentificacion = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $this->getArea()
            ]);

            $capture = $stmt->fetchColumn();

            return $capture ?: '';
        } catch (PDOException $err) {
            echo "Ha ocurrido un error inesperado en la consulta de datos $err";
            return false;
        }
    }
}
