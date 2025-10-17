<?php

class HomeController extends ControllerViews {
    public function index() {
        $this->views('inicio');
    }
}