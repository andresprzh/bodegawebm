<?php

class ModeloTransportador extends Conexion{
    /* ============================================================================================================================
                                                        ATRIBUTOS  
    ============================================================================================================================*/
    
    private $transportador;

    /* ============================================================================================================================
                                                        CONSTRUCTOR   
    ============================================================================================================================*/
    function __construct($transportador) {

        $this->transportador=$transportador;
        parent::__construct();

    }

      /* ============================================================================================================================
                                                        FUNCIONES   
    ============================================================================================================================*/
    public function mdlMostrarDestinos()
    {   
        $tra=$this->transportador;
        $stmt= $this->link-> prepare("SELECT requisicion.lo_origen,requisicion.lo_destino,sedes.descripcion,sedes.direccion1
        FROM caja
        INNER JOIN pedido ON caja.no_caja=pedido.no_caja
        INNER JOIN requisicion ON pedido.no_req=requisicion.no_req
        INNER JOIN sedes ON requisicion.lo_destino=sedes.codigo
        WHERE DATE(caja.enviado)=DATE(NOW())
        AND transportador=:transportador
        GROUP BY requisicion.lo_origen,requisicion.lo_destino;");

        //para evitar sql injection
        $stmt->bindParam(":transportador",$tra,PDO::PARAM_INT);
        //ejecuta el comando sql
        $stmt->execute();

        // return $stmt->fetchAll();
        return  $stmt;
        $stm=null;
    }

    public function mdlMostrarPedidos()
    {
        $tra=$this->transportador;
        $stmt= $this->link-> prepare("SELECT requisicion.lo_origen,requisicion.lo_destino,
        sedes.descripcion,sedes.direccion1 as direccion,caja.tipo_caja,caja.no_caja
        FROM caja
        INNER JOIN pedido ON caja.no_caja=pedido.no_caja
        INNER JOIN requisicion ON pedido.no_req=requisicion.no_req
        INNER JOIN sedes ON requisicion.lo_destino=sedes.codigo
        WHERE DATE(caja.enviado)=DATE(NOW())
        AND transportador=:transportador
        AND caja.estado=2
        GROUP BY requisicion.lo_origen,requisicion.lo_destino,sedes.descripcion,sedes.direccion1,caja.tipo_caja,caja.no_caja
        ORDER BY requisicion.lo_destino ASC,caja.tipo_caja ASC");

        //para evitar sql injection
        $stmt->bindParam(":transportador",$tra,PDO::PARAM_INT);
        //ejecuta el comando sql
        $stmt->execute();

        // return $stmt->fetchAll();
        return  $stmt;
        $stm=null;
    }

    public function mdlEntregarCaja($numcaja){
        $stmt= $this->link->prepare('UPDATE caja SET estado=3,recibido=NOW() WHERE no_caja=:no_caja');
        $stmt->bindParam(":no_caja",$numcaja,PDO::PARAM_INT);
        
        $res=$stmt->execute();
        $stmt->closeCursor();
        // retorna el resultado de la sentencia
	    return $res;
          
        // cierra la conexion
        $stmt=null;
    }
}