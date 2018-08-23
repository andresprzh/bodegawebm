<?php


include "../controladores/alistar.controlador.php";

require "../modelos/conexion.php";

require "../modelos/alistar.modelo.php";

/* ============================================================================================================================
                                                ELIMINA 1 ITEM DE LA CAJA
============================================================================================================================*/
$cod_barras=$_POST['iditem'];

$req=$_POST["req"];

//crea objeto controlador 
$controlador=new ControladorAlistar($req);

$resultado=$controlador->ctrEliminarItemCaja($cod_barras);

print json_encode($resultado);
