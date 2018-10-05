<?php

class ControladorAlistar {
    /* ============================================================================================================================
                                                        ATRIBUTOS   
    ============================================================================================================================*/
    protected $req;
    private $modelo;

    /* ============================================================================================================================
                                                        CONSTRUCTOR   
    ============================================================================================================================*/
    function __construct($req) {
        
        $this->req=$req;
        $this->modelo=new ModeloAlistar($req);

    }

    /* ============================================================================================================================
                                                        FUNCIONES   
    ============================================================================================================================*/

    // busca los o el item en la tabla de alistado
    public function ctrBuscarItem($cod_barras)
    {
        
        // busca el numero de la ultima acaja abierta por el usuario
        $busqueda = $this->modelo->mdlMostrarNumCaja();
        $numcaja = ($busqueda->fetch());
        $numcaja = $numcaja['numcaja'];
        // libera conexion para hace otra sentencia
        $busqueda->closeCursor();

        $busqueda=$this->modelo->mdlMostrarItems($cod_barras);

        if ($busqueda->rowCount() > 0) {

                        
            while($row = $busqueda->fetch()){

                //solo muestra los items que no estan alistados
                $itembus["estado"]=$row['estado'];                        
                $itembus["contenido"]=["codigo"=>$row["ID_CODBAR"],
                                        "iditem"=>$row["item"],  
                                        "referencia"=>$row["ID_REFERENCIA"],
                                        "descripcion"=>$row["DESCRIPCION"],
                                        "disponibilidad"=>$row["disp"],
                                        "pedido"=>$row["pedido"],
                                        "pendientes"=>$row["pendientes"],
                                        "alistados"=>$row["alistado"],
                                        "caja"=>$row["no_caja"],
                                        "alistador"=>$row["nombre"],
                                        "ubicacion"=>$row["ubicacion"],
                                        "origen"=>$row["lo_origen"],
                                        "destino"=>$row["lo_destino"]
                                        ];
                
                
                // comprueba el estado del item pedido
                switch ($itembus["estado"]) {

                    //0 si encontro algun resultaod en la consulta
                    case 0:
                        $itembus["estado"]="encontrado";
                        break;

                    // 1 si el item ya esta siendo alistado pro alguien
                    case 1:
                        $itembus["estado"]="error1";
                        $itembus["contenido"]="El item ya fue Alistado";
                        return $itembus;
                        break;

                }
                // si el item ya esta en la caja del alistador se regresa un mensaje informando 
                if ($numcaja==$row["no_caja"]) {
                    $itembus["estado"]="error2";
                    $itembus["contenido"]="Item ya esta en la caja";
                    return $itembus;
                }
                
            }
            
            
            //retorna el item a la funcion
            return $itembus;

        //si no encuentra resultados devuelve "error"
        }else{

            return ['estado'=>"error",
                    'contenido'=>"Item no encontrado en la base de datos!"];

        }
    }

    // busca todos los items de la tabla pedido de una requisicion
    public function ctrBuscarItemsReq()
    {
        $busqueda=$this->modelo->mdlMostrarItems('%%');
        if ($busqueda->rowCount() > 0) {

            
            $itembus["estado"]="encontrado";

            $cont=0;

            while($row = $busqueda->fetch()){

                //solo muestra los items que no estan alistados
                if($row["estado"]==0){
                    
                    // se usa el id del item como el index en el arreglo
                    // si se encuentra 2 veces el mismo item este se remplaza
                    $itembus["contenido"][$row["item"]]=["codigo"=>$row["ID_CODBAR"],
                                        "referencia"=>$row["ID_REFERENCIA"],
                                        "descripcion"=>$row["DESCRIPCION"],
                                        "disponibilidad"=>$row["disp"],
                                        "pendientes"=>$row["pendientes"],
                                        "alistados"=>$row["alistado"],
                                        'ubicacion'=>$row["ubicacion"]
                                        ];
                    
                    $cont++;
                    $itembus["ubicaciones"][$cont]=$row["ubicacion"];                          
                }

            }
            
            if ($cont>0) {
                $itembus["ubicaciones"]=array_unique($itembus["ubicaciones"]);
            }
            
            return $itembus;


        //si no encuentra resultados devuelve "error"
        }else{

            return ['estado'=>"error",
                    'contenido'=>"Item no encontrado en la base de datos!"];

        }
    }

    // busca items por fuera de la requisicion
    public function ctrBuscarIE($item)
    {
        $busqueda=$this->modelo->mdlMostrarIE($item);
        
        // return $busqueda;
        if ($busqueda->rowCount() > 0) {

            if($busqueda->rowCount() == 1){

                $row = $busqueda->fetch();

                //guarda los resultados en un arreglo
                $itembus=["estado"=>"encontrado",
                           "contenido"=> ["codigo"=>$row["ID_CODBAR"],
                                           "iditem"=>$row["ID_ITEM"],  
                                           "referencia"=>$row["ID_REFERENCIA"],
                                           "descripcion"=>$row["DESCRIPCION"],
                                         ]
                         ];
                
               
                return $itembus;

            }else {

                $itembus["estado"]=["encontrado"];

                $cont=0;

                while($row = $busqueda->fetch()){
                        
                    $itembus["contenido"][$cont]=["codigo"=>$row["ID_CODBAR"],
                                        "iditem"=>$row["ID_ITEM"],  
                                        "referencia"=>$row["ID_REFERENCIA"],
                                        "descripcion"=>$row["DESCRIPCION"],
                                        ];
                    $cont++;

                }

                return $itembus;

            }

        //si no encuentra resultados devuelve "error"
        }else{

            return ['estado'=>"error",
                    'contenido'=>"Item no encontrado!"];

        }
    }

    // agrega un item extra a la requisicion
    public function ctrAgregarIE($items)
    {
        $resultado=$this->modelo->mdlAgregarIE($items);
        return $resultado;
    }

    //crea una caja si no existe
    public function ctrCrearCaja()
    {
        
        $busqueda=$this->modelo->mdlMostrarNumCaja();

        
        $row=$busqueda->fetch();
        
        
        //si tiene cajas sin cerrar no crea una nueva
        if ($row['numcaja']) {
            // libera conexion para hace otra sentencia
            $busqueda->closeCursor();
            //busca los items en la caja
            $resultado=$this->ctrBuscarItemCaja($row['numcaja']);
            $resultado["estadocaja"]="yacreada";
            return $resultado ;
        // si no tiene cajas sin cerrar crea otra caja
        }else{

            // libera conexion para hace otra sentencia
            $busqueda->closeCursor();

            
            

            //crea una caja nueva
            if ($this->modelo->mdlCrearCaja()) {
                $resultado["estadocaja"]="creada";
                return $resultado;

            }else {
                $resultado["estadocaja"]="error";
                return $resultado;

            }

        }

    }

    // busca los items de 1 caja
    public function ctrBuscarItemCaja($numcaja)
    {
        
        $busqueda=$this->modelo->mdlMostrarItemsCaja($numcaja);

        if ($busqueda->rowCount() > 0) {

            $itembus["estado"]="encontrado";

            $cont=0;

            while($row = $busqueda->fetch()){
                
                // si hay cajas sin cerrar en otra requisicion
                if ($row['no_req']!=$this->req[0]) {
                    $itembus=['estado'=>"error2",
                    'contenido'=>$row['no_req']];
                    return $itembus;
                    break;
                }
                $itembus["contenido"][]= $row;                 

            }

            
            
            return $itembus;

        //si no encuentra resultados devuelve "error"
        }else{

            return ['estado'=>"error",
                    'contenido'=>"Caja sin Items!"];

        }

    }
    
    //cierra la caja
    public function ctrCerrarCaja($items,$req,$tipocaja,$pesocaja,$numcaja=null)
    {
        
        // busca el numero de la ultima acaja abierta por el usuario
        if ($numcaja==null) {
            $busqueda = $this->modelo->mdlMostrarNumCaja();
            $numcaja = ($busqueda->fetch());
            $numcaja = $numcaja['numcaja'];
        }
                
        for ($i=0; $i <count($items) ; $i++) { 
            $resultado=$this->modelo->mdlAlistarItem($items[$i],$numcaja);
        }
        
        if ($resultado) {
            $resultado=$this->modelo->mdlCerrarCaja($tipocaja,$pesocaja,$numcaja);
        }

        return $resultado;
    }

    // elimina 1 item de una caja
    public function ctrEliminarItemCaja($cod_barras,$no_caja=null)
    {
        
        if ($no_caja==null) {
            $busqueda=$this->modelo->mdlMostrarNumCaja();
            $row=$busqueda->fetch();
            $no_caja=$row['numcaja'];
        }

        return $this->modelo->mdlEliminarItemCaja($cod_barras,$no_caja);

    }

    // alista 1 solo item en la caja
    public function ctrAlistarItem($item)
    {
        // busca el numero de la ultima acaja abierta por el usuario
        $busqueda = $this->modelo->mdlMostrarNumCaja();
        $numcaja = ($busqueda->fetch());
        $numcaja = $numcaja['numcaja'];

        $resultado=$this->modelo->mdlAlistarItem($item,$numcaja);

        return $resultado;   

    }

}
