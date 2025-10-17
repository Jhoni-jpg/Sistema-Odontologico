<?php

class VistasPermitidas
{
    public $vistas = [
        'Inicio',
        'Asignar',
        'Consultar',
        'Cancelar',
        'Paciente'
    ];

    public function validacionVistas($vista)
    {
        return is_string($vista) && in_array($vista, $this->vistas);
    }
}
