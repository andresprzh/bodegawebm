<?php
include "controladores/principal.controlador.php";
include "controladores/usuarios.controlador.php";

require "modelos/conexion.php";
require "modelos/usuarios.modelo.php";
require "modelos/requerir.modelo.php";

$principal= new ControladorPrincipal();
