<?php
require_once __DIR__ . '/../../others/config/vistasPermitidas.php';

class ControllerViews
{
    public function views($vista, $data = [])
    {
        $rutaVista = __DIR__ . '/../../views/' . ucfirst($vista) . '.php';

        if (!file_exists($rutaVista)) {
            throw new Exception("Vista no encontrada '$vista'");
        }

        extract($data);
        require_once $rutaVista;
    }
}