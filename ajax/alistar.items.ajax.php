<?php


include "../controladores/alistar.controlador.php";

require "../modelos/conexion.php";

require "../modelos/alistar.modelo.php";

/* ============================================================================================================================
                                                MUESTRA LOS ITEMS DELA reqUISICION 
============================================================================================================================*/

// obtienen los datos dela requisicion (numero requisicion y codigo alistador)
$req=$_POST["req"];
//crea objeto controlador 
$controlador=new ControladorAlistar($req);


// si se pasa el codigo de barras del item se busca dicho item y se crea la caja a nombre del alistador que realizo el login
if (isset($_POST['codigo'])) {

    $cod_barras=$_POST['codigo'];

// si no se paso el codigo de barras busca todos los items de la requisicion  seleccionada
}else{

    $cod_barras='%%';

}

// regresa el resultado de la buqueda como un objeto JSON
$respuesta=$controlador->ctrBuscarItems($cod_barras);

// muestra el vector como dato JSON
print json_encode( $respuesta);