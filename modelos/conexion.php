<?php

class Conexion{
    protected $link;

    function __construct() {
        /*==========================================
                     CONECTAR BASE DE DATOS
          ========================================*/
          $link=new PDO("mysql:host=localhost;dbname=bodegadrogueria",
          "root",
          "");

        
        //permite usar caracteres latinos
        $link->exec("set names utf8");

        $this->link = $link;
    }


    protected function buscaritem($tabla,$item,$valor){
         // si item es diferente de nullo se busca con una condicion
        
        if ($item!=null) {
          
            $stmt= $this->link-> prepare("SELECT * FROM $tabla WHERE $item = :$item");
            
            
            //para evitar sql injection
            $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
            //ejecuta el comando sql
            $stmt->execute();
            
            
            // return $stmt->fetch();

            return $stmt;
            
            
          // si item es nulo muestra todos los datos de $tabla
          }else {
  
            $stmt= $this->link-> prepare("SELECT * FROM $tabla");
            //para evitar sql injection
            $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
            //ejecuta el comando sql
            $stmt->execute();
  
            // return $stmt->fetchAll();
            return  $stmt;
          }
  
          // cierra conexion base de datos
          $stmt=null;
    }

}