<?php
class ControladorUsuarios {

    private $modelo;

    function __construct() {

        $this->modelo=new ModeloUsuarios();

    }
   
    /*==================================================
                    INGREOS DE USUARIO
    ================================================*/
    public function ctrIngresoUsuario(){
        if (isset($_POST["usuario"])) {
            if (preg_match('/^[a-zA-Z0-9]+$/',$_POST["usuario"]) &&
            (preg_match('/^[a-zA-Z0-9]+$/',$_POST["contraseña"]))) {
                
                //busca en la tabla usuario en la columna usuario al dato o $valor
                
                $item="usuario";

                //obtiene el usuario ingresado
                $valor=$_POST["usuario"];
                //obtiene la contrseña ingresada
                $contraseña=$_POST["contraseña"];
                
                                
                $respuesta=$this->modelo->mdlMostrarUsuarios($item,$valor);
                $respuesta=$respuesta->fetch();
                
                //si encuentra el usuario inicia sesion

                if(strcasecmp($respuesta["usuario"],$valor)==0 &&
                password_verify($contraseña, $respuesta["password"]) && 
                $respuesta["perfil"]!=5){

                    $_SESSION["iniciarSesion"]="ok";
                    $_SESSION["usuario"]=["id" => $respuesta["id_usuario"],
                                          "nombre" => $respuesta["nombre"],
                                          "cedula" => $respuesta["cedula"],  
                                          "usuario" => $respuesta["usuario"],
                                          "perfil" => $respuesta["perfil"]
                                        ];
                    if ($respuesta["perfil"]==3) {
                        echo '<script>
                            window.location="alistar";
                          </script>';
                    }else {
                        echo '<script>
                            window.location="transportador";
                          </script>';
                    }
                    
                    
                    
                //de lo contrario muestra un mensaje de alerta
                }else {
                    echo '<br><div class="card-panel  red darken-4">Error al ingresas, vuelva a intentar</div>';
                }
                
            }
        }
    }
}