<?php


include "../controladores/alistar.controlador.php";

require "../modelos/conexion.php";

require "../modelos/alistar.modelo.php";

/* ============================================================================================================================
                                                CREA Y/O MUESTRA LOS ITEMS DE LA CAJA
============================================================================================================================*/
// obtienen los datos de la requisicion (numero requisicion y codigo alistador)
$req=$_POST["req"];
//crea objeto controlador 
$controlador=new ControladorAlistar($req);


//crea caja nueva para el usuario
$respuesta=$controlador->ctrCrearCaja();


// muestra el vector como dato JSON
print json_encode( $respuesta);