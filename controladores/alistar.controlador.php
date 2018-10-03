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
    public function ctrBuscarItems($cod_barras){
        
        $busqueda=$this->modelo->mdlMostrarItems($cod_barras);
        

        if ($busqueda->rowCount() > 0) {

            if($busqueda->rowCount() == 1){

                $row = $busqueda->fetch();

                //guarda los resultados en un arreglo
                $itembus=["estado"=>$row['estado'],
                           "contenido"=> ["codigo"=>$row["ID_CODBAR"],
                                           "iditem"=>$row["item"],  
                                           "referencia"=>$row["ID_REFERENCIA"],
                                           "descripcion"=>$row["DESCRIPCION"],
                                           "disponibilidad"=>$row["disp"],
                                           "pedidos"=>$row["pedido"],
                                           "alistados"=>$row["alistado"],
                                           "caja"=>$row["no_caja"],
                                           "alistador"=>$row["nombre"],
                                           "ubicacion"=>$row["ubicacion"],
                                           "origen"=>$row["lo_origen"],
                                           "destino"=>$row["lo_destino"]
                                         ]
                         ];
                
                // en el arreglo se guarda el estado de la consulta         
                switch ($itembus["estado"]) {

                    //0 si encontro algun resultaod en la consulta
                    case 0:
                        $itembus["estado"]="encontrado";
                        break;

                    // 1 si el item ya esta siendo alistado pro alguien
                    case 1:
                        $itembus["estado"]="error1";
                        $itembus["contenido"]="El item ya fue Alsitado";
                        break;

                }
                //retorna el item a la funcion
                return $itembus;

            }else {

                $itembus["estado"]=["encontrado"];

                $cont=0;

                while($row = $busqueda->fetch()){

                    //solo muestra los items que no estan alistados
                    if($row["estado"]==0){
                        
                        $itembus["contenido"][]=["codigo"=>$row["ID_CODBAR"],
                                           "iditem"=>$row["item"],  
                                           "referencia"=>$row["ID_REFERENCIA"],
                                           "descripcion"=>$row["DESCRIPCION"],
                                           "disponibilidad"=>$row["disp"],
                                           "pedidos"=>$row["pedido"],
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

            }

        //si no encuentra resultados devuelve "error"
        }else{

            return ['estado'=>"error",
                    'contenido'=>"Item no encontrado en la base de datos!"];

        }
    }

    //crea una caja si no existe
    public function ctrCrearCaja(){
        
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
    public function ctrBuscarItemCaja($numcaja){
        
        $busqueda=$this->modelo->mslMostrarItemsCaja($numcaja);

        if ($busqueda->rowCount() > 0) {

            $itembus["estado"]="encontrado";

            $cont=0;
            $itembus["contenido"]= $busqueda->fetchAll();   
            
            return $itembus;

        //si no encuentra resultados devuelve "error"
        }else{

            return ['estado'=>"error",
                    'contenido'=>"Caja sin Items!"];

        }

    }
    
    //cierra la caja
    public function ctrCerrarCaja($tipocaja,$items,$req){
        // busca el numero de la ultima acaja abierta por el usuario
        $busqueda = $this->modelo->mdlMostrarNumCaja();
        $numcaja = ($busqueda->fetch());
        $numcaja = $numcaja['numcaja'];
        for ($i=0; $i <count($items) ; $i++) { 
            $resultado=$this->modelo->mdlAlistarItem($items[$i],$numcaja);
        }
        if ($resultado) {
            $resultado=$this->modelo->mdlCerrarCaja($tipocaja,$numcaja);
        }

        return $resultado;
    }

    // elimina 1 item de una caja
    public function ctrEliminarItemCaja($cod_barras,$no_caja=null){
        
        if ($no_caja==null) {
            $busqueda=$this->modelo->mdlMostrarNumCaja();
            $row=$busqueda->fetch();
            $no_caja=$row['numcaja'];
        }

        return $this->modelo->mdlEliminarItemCaja($cod_barras,$no_caja);

    }

    // alista 1 solo item en la caja
    public function ctrAlistarItem($item){
        // busca el numero de la ultima acaja abierta por el usuario
        $busqueda = $this->modelo->mdlMostrarNumCaja();
        $numcaja = ($busqueda->fetch());
        $numcaja = $numcaja['numcaja'];

        $resultado=$this->modelo->mdlAlistarItem($item,$numcaja);

        return $resultado;   

    }
}
