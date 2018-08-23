<?php


class ModeloUsuarios extends Conexion {
    

    function __construct() {
      
      parent::__construct();

    }
    


    /*==========================================
            MOSTRAR USUARIOS
      ========================================*/
    public function mdlMostrarUsuarios($item=null,$valor=null){
      $tabla='usuario';
      
      //busca los itemas
      return $this->buscaritem($tabla,$item,$valor);
      
    }

    /*==========================================
          REGISTRAR USUARIO
    ========================================*/
    public function mdlRegistrarUsuario($datos){
      $tabla='usuario';
      $stmt= $this->link->prepare("INSERT INTO $tabla(nombre,cedula,usuario,password,perfil) VALUES(:nombre,:cedula,:usuario,:password,:perfil);");
      
      $stmt->bindParam(":nombre",$datos["nombre"],PDO::PARAM_STR);
      $stmt->bindParam(":cedula",$datos["cedula"],PDO::PARAM_STR);
      $stmt->bindParam(":usuario",$datos["usuario"],PDO::PARAM_STR);
      $stmt->bindParam(":password",$datos["password"],PDO::PARAM_STR);
      $stmt->bindParam(":perfil",$datos["perfil"],PDO::PARAM_STR);
      

      $res=$stmt->execute();
       
      return $res;

      $stmt=null;
    }

    public function mdlMostrarPerfiles($item=null,$valor=null){
      $tabla='perfiles';
      
      //busca los itemas
      return $this->buscaritem($tabla,$item,$valor);
    }  

    public function mdlCambiarUsuario($datos){
      $tabla='usuario';
      $stmt= $this->link->prepare("UPDATE $tabla SET nombre=:nombre, cedula=:cedula,usuario=:usuario,password=:password,perfil=:perfil WHERE id_usuario=:id_usuario;");
      
      $stmt->bindParam(":id_usuario",$datos["id"],PDO::PARAM_INT);
      $stmt->bindParam(":nombre",$datos["nombre"],PDO::PARAM_STR);
      $stmt->bindParam(":cedula",$datos["cedula"],PDO::PARAM_STR);
      $stmt->bindParam(":usuario",$datos["usuario"],PDO::PARAM_STR);
      $stmt->bindParam(":password",$datos["password"],PDO::PARAM_STR);
      $stmt->bindParam(":perfil",$datos["perfil"],PDO::PARAM_INT);
      

      $res=$stmt->execute();
        
      return $res;
      
      $stmt=null;
    }  
  

}