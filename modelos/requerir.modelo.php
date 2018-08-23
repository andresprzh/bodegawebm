<?php

class ModeloRequierir extends Conexion{
    
    
    function __construct() {
      
        parent::__construct();
        
    }

    public function mdlMostrarReq($item,$valor=null){
    
        $tabla='requisicion';
        if ($valor==null) {
            $stmt= $this->link-> prepare("SELECT * FROM $tabla WHERE $item = 0");
        }else {
            $stmt= $this->link-> prepare("SELECT * FROM $tabla WHERE $item = :$item");
        }
        
        //para evitar sql injection
        $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
        //ejecuta el comando sql
        $stmt->execute();
        //busca las requisiciones
        // return $this->buscaritem($tabla,$item,$valor);
        return $stmt;
    }

    public function mdlMostrarItem($item,$valor){
        
        $tabla='ITEMS';

        //busca los items
        return $this->buscaritem($tabla,$item,$valor);
    }

    public function mdlMostrarItems($req){
       
        $stmt= $this->link->prepare('SELECT pedido.item,pedido.estado,pedido.no_req,pedido,pedido.disp,pedido.alistado,pedido.ubicacion,
		MIN(COD_BARRAS.ID_CODBAR) AS ID_CODBAR,ITEMS.ID_REFERENCIA, ITEMS.ID_REFERENCIA,ITEMS.DESCRIPCION,
		caja.no_caja,usuario.nombre,requisicion.lo_origen,requisicion.lo_destino
		FROM COD_BARRAS
		INNER JOIN ITEMS ON ID_ITEM=ID_ITEMS	
		INNER JOIN pedido ON Item=ID_ITEM	
		INNER JOIN requisicion ON requisicion.no_req=pedido.no_req
		LEFT JOIN caja ON caja.no_caja=pedido.no_caja
		LEFT JOIN usuario ON id_usuario=caja.alistador
		WHERE pedido.no_req = :no_req
		GROUP BY  pedido.item,pedido.estado,pedido.no_req,pedido,pedido.disp,pedido.alistado,pedido.ubicacion;');

        
        $stmt->bindParam(":no_req",$req,PDO::PARAM_STR);
        

        $stmt->execute();

        
        // $stmt->closeCursor();
        return $stmt;

        // cierra la conexion
        $stmt=null;
    }

    public function mdlSubirReq($cabecera,$items){
        $tabla="requisicion";
        
        $stmt= $this->link->prepare("INSERT INTO $tabla(no_req,creada,lo_origen,lo_destino,tip_inventario,solicitante) VALUES(:no_req,:fecha,:lo_origen,:lo_destino,:tip_inventario,:solicitante)");
        
        $stmt->bindParam(":no_req",$cabecera[0],PDO::PARAM_STR);
        $stmt->bindParam(":fecha",$cabecera[1],PDO::PARAM_STR);
        // $stmt->bindParam(":hora",$cabecera[2],PDO::PARAM_STR);
        $stmt->bindParam(":lo_origen",$cabecera[2],PDO::PARAM_STR);
        $stmt->bindParam(":lo_destino",$cabecera[3],PDO::PARAM_STR);
        $stmt->bindParam(":tip_inventario",$cabecera[4],PDO::PARAM_INT);
        $stmt->bindParam(":solicitante",$cabecera[5],PDO::PARAM_STR);
        
        
        $stmt->execute();
        
        $tabla="pedido";

        $sql='INSERT INTO '.$tabla.'(item,no_req,ubicacion,disp,pedido,alistado) VALUES'. $items;
        
        $stmt= $this->link->prepare($sql);
        
        $res= $stmt->execute();
        $stmt->closeCursor();
        return $res;
    }

    public function mdlEliReq($req)
    {
        $tabla="requisicion";
        $stmt= $this->link->prepare("DELETE FROM $tabla WHERE no_req=:no_req");
        $stmt->bindParam(":no_req",$req,PDO::PARAM_STR);

        $res= $stmt->execute();
        $stmt->closeCursor();
        return $res;
    }
}