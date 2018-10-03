<?php

include "../controladores/alistar.controlador.php";


require "../modelos/conexion.php";
require "../modelos/alistar.modelo.php";
require "../modelos/requerir.modelo.php";

if (isset($_GET['ruta'])) {
    

    switch ($_GET['ruta']) {

        /* ============================================================================================================================
                                                CREA CAJA NUEVA PARA EL USUARIO
        ============================================================================================================================*/
        case 'cajas':

            // obtienen los datos de la requisicion (numero requisicion y codigo alistador)
            $req=$_POST["req"];
            //crea objeto controlador 
            $controlador=new ControladorAlistar($req);


            //crea caja nueva para el usuario
            $respuesta=$controlador->ctrCrearCaja();


            // muestra el vector como dato JSON
            print json_encode( $respuesta);

            break;

        /* ============================================================================================================================
                                                ELIMINA 1 ITEM DE LA CAJA
        ============================================================================================================================*/
        case 'eliminaritem':
            $cod_barras=$_POST['iditem'];

            $req=$_POST["req"];
            
            //crea objeto controlador 
            $controlador=new ControladorAlistar($req);
            
            $resultado=$controlador->ctrEliminarItemCaja($cod_barras);
            
            print json_encode($resultado);
            
            break;

        /* ============================================================================================================================
                                                CIERRA LA CAJA
        ============================================================================================================================*/
        case 'empacar':
            // obtienen los datos dela requisicion (numero requisicion y codigo alistador)
            $req=$_POST["req"];
            $tipocaja=$_POST['tipocaja'];
            $items=$_POST['items'];

            //crea objeto controlador 
            $controlador=new ControladorAlistar($req);

            $respuesta=$controlador->ctrCerrarCaja($tipocaja,$items,$req);

            print json_encode($respuesta);

            
            break;

        /* ============================================================================================================================
                                                MUESTRA LOS ITEMS DELA reqUISICION 
        ============================================================================================================================*/
        case 'items':
            
            // obtienen los datos dela requisicion (numero requisicion y codigo alistador)
            $req=$_REQUEST['req'];
            
            // BUSCA TODOS LOS ITEMS DE LA REQUISICION
            if ($_SERVER['REQUEST_METHOD']==='GET') {
                
                //crea objeto controlador 
                $controlador=new ControladorAlistar($req);

                //si se buca un item en epsecifico
                if (isset($_GET['codigo'])) {

                    $cod_barras=$_GET['codigo'];
                // de lo contrario se muestran todos los items    
                }else {
                    $cod_barras='%%';
                }
                // regresa el resultado de la buqueda como un objeto JSON
                $respuesta=$controlador->ctrBuscarItems($cod_barras);
                print json_encode( $respuesta);

            // BUSCA UN ITEM ESPECIFICO DE LA REQUISICION
            }else{
                //crea objeto controlador 
                $controlador=new ControladorAlistar($req);


                $item=$_POST['item'];

                // regresa el resultado de la buqueda como un objeto JSON
                $respuesta=$controlador->ctrAlistarItem($item);

                // muestra el vector como dato JSON
                print json_encode( $respuesta);
            }
            break;

        /* ============================================================================================================================
                                                BUSCA LAS REQUISICIONES
        ============================================================================================================================*/
        case 'requisiciones':
            $modelo=new ModeloRequierir();
            $item='estado';
            
            $res=$modelo->mdlMostrarReq($item);
            
            
            $cont=0;//contador para almacenar los datos en un vector
            
            // si hay resultados los regresa como json
            if ($res->rowCount()) {
                while($row = $res->fetch()) {
                    //almacena la busqueda en un vector
                    $req[$cont]=$row["no_req"];
                    // $req[$cont]=$row;
                    //aumenta el contador
                    $cont++;
                }
                // muestra el vector     como dato JSON
                print json_encode($req);
            }            
            break;
            
            
        default:
            print json_encode("Best REST API :D");
            break;
    }
}
