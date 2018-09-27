<?php

class ControladorTransportador{

      /* ============================================================================================================================
                                                        ATRIBUTOS   
    ============================================================================================================================*/
    
    private $modelo;
    private $transportador;
    /* ============================================================================================================================
                                                        CONSTRUCTOR   
    ============================================================================================================================*/
    function __construct($transportador=null) {

        $this->transportador=$transportador;
        $this->modelo=new ModeloTransportador($transportador);

    }

    /* ============================================================================================================================
                                                        FUNCIONES   
    ============================================================================================================================*/

    public function ctrBuscarPedidos(){
        $busqueda=$this->modelo->mdlMostrarPedidos();
        // $lo_destino=$busqueda->fetchAll()[0]['lo_destino'];
        
        
        if ($busqueda->rowCount()>0) {

                $resultado["estado"]="encontrado";
                // $resultado
                // $resultado["contenido"]=$busqueda->fetchAll();
                // guarda datos segun el destino y hace un conteo de la cantidad de cajas segun tipo
                $datos=$busqueda->fetchAll(); 
                for ($i=0; $i <count($datos) ; $i++) { 
                    
                    //almacena el numero de caja del pedido en apartado cajas
                    if (!isset($resultado["contenido"][$datos[$i]['lo_destino']]["cajas"][$datos[$i]['no_caja']])) {
                        
                        $resultado["contenido"][$datos[$i]['lo_destino']]["cajas"][]=$datos[$i]['no_caja'];

                    }

                    // almacena en en el apartado tipo_cajas, el tipo de cajas y el numero de cajas de dicho tipo
                    if (!isset($resultado["contenido"][$datos[$i]['lo_destino']]["tipo_cajas"][$datos[$i]['tipo_caja']])) {

                        $resultado["contenido"][$datos[$i]['lo_destino']]["tipo_cajas"][$datos[$i]['tipo_caja']]=1;

                    }else {

                        $resultado["contenido"][$datos[$i]['lo_destino']]["tipo_cajas"][$datos[$i]['tipo_caja']]+=1;

                    }

                    // almacena en el apartado descripcion el nombre de la sede del pedido
                    if (!isset($resultado["contenido"][$datos[$i]['lo_destino']]["descripcion"][$datos[$i]['tipo_caja']])) {

                        $resultado["contenido"][$datos[$i]['lo_destino']]["descripcion"]=[$datos[$i]['descripcion']];

                    }

                    // se almacena en direccion la direccion de la sede del pedid
                    if (!isset($resultado["contenido"][$datos[$i]['lo_destino']]["direccion"][$datos[$i]['tipo_caja']])) {

                        $resultado["contenido"][$datos[$i]['lo_destino']]["direccion"]=[$datos[$i]['direccion']];

                    }
                    
                    // almacena el total de cajas en el pedido
                    if (!isset($resultado["contenido"][$datos[$i]['lo_destino']]["cantidad"])) {

                        $resultado["contenido"][$datos[$i]['lo_destino']]["cantidad"]=1;

                    }else {

                        $resultado["contenido"][$datos[$i]['lo_destino']]["cantidad"]+=1;

                    }

                }   

        }else {

            $resultado["estado"]=false;
            $resultado["contenido"]="No tiene pedidos";

        }
        return $resultado;
    }

    public function ctrEntregarCajas($cajas){
        $resultado=true;
        for ($i=0; $i <count($cajas) ; $i++) { 
            // echo $cajas[$i];
            $resultado=$this->modelo->mdlEntregarCaja($cajas[$i]);
        }
        return $resultado;
    }
}