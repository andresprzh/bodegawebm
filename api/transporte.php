<?php

// include "../controladores/alistar.controlador.php";
include "../controladores/transportador.controlador.php";


require "../modelos/conexion.php";
require "../modelos/transportador.modelo.php";

if (isset($_GET['ruta'])) {
    

    switch ($_GET['ruta']) {
        
        case "destinos":
            $usuario=$_GET['usuario'];
            $modelo=new ModeloTransportador($usuario);
            $busqueda=$modelo->mdlMostrarDestinos('usuario','perfil',6);
            
            
            if ($busqueda->rowCount()>0) {

                $resultado["estado"]="encontrado";
                $resultado["contenido"]=$busqueda->fetchAll();

            }else {

                $resultado["estado"]=false;
                $resultado["contenido"]="No se encontraron ubicaciones";

            }
            print json_encode($resultado);
            break;
        
        case "pedidos":
            $usuario=$_GET["usuario"];
            $controlador= new ControladorTransportador($usuario);
            $resultado=$controlador->ctrBuscarPedidos();
            print json_encode($resultado);
            break;

        case "entregar":
            $cajas=$_POST["cajas"];
            $controlador= new ControladorTransportador();
            $resultado=$controlador->ctrEntregarCajas($cajas);
            print json_encode($resultado);
            break;

    }
    
}