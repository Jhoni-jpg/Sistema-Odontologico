<?php
require_once __DIR__ . '/../../others/config/vistasPermitidas.php';

class ControllerViews
{
    public function views($vista)
    {
        $rutaVista = __DIR__ . '/../../views/' . ucfirst($vista) . '.php';

        if (!file_exists($rutaVista)) {
            throw new Exception("Vista no encontrada '$vista'");
        }

        require_once $rutaVista;
    }
}