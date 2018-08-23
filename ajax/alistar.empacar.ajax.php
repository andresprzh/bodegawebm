<?php


include "../controladores/alistar.controlador.php";

require "../modelos/conexion.php";

require "../modelos/alistar.modelo.php";

/* ============================================================================================================================
                                                CIERRA LA CAJA
============================================================================================================================*/
// obtienen los datos dela requisicion (numero requisicion y codigo alistador)
$req=$_POST["req"];
$tipocaja=$_POST['tipocaja'];
$items=$_POST['items'];

//crea objeto controlador 
$controlador=new ControladorAlistar($req);

$respuesta=$controlador->ctrCerrarCaja($tipocaja,$items,$req);

print json_encode($respuesta);

