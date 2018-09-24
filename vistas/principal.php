<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>

    
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bodega</title>
    <link rel="icon" href="vistas/imagenes/plantilla/logo.png">

<!-- ============================================================================================================================
                            ESTILOS
  ============================================================================================================================ -->
<!-- Font Awesome -->
<link rel="stylesheet" href="vistas\lib\font-awesome\css\all.css">


<!-- Estilos -->
<link rel="stylesheet" href="vistas/css/style.css">

<!-- Materialize -->
<link rel="stylesheet" href="vistas/lib/materialize/css/materialize.css">

 


<!-- ============================================================================================================================
                        PLUGINS JAVASCRIPT
============================================================================================================================= -->
<!-- jQuery 3 -->
<script src="vistas/lib/jquery/dist/jquery.min.js"></script>



<!-- SweetAlert 2 -->
<script src="vistas/plugins/sweetalert2/sweetalert.min.js"></script>

<!-- Materialize -->
<script src="vistas/lib/materialize/js/materialize.js"></script>
<script src="vistas/lib/materialize/js/init.js"></script>

</head>
<!-- ============================================================================================================================
                            CUERPO DOCUMENTO
  ============================================================================================================================= -->
<body class="" >

  <?php
  
  if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok" && in_array($_SESSION["usuario"]["perfil"],[3,6])){
    
    /* ============================================================================================================================
                BARRA DE NAVEGACION
    ============================================================================================================================== */
    include "modulos/navbar.php";
    
    echo "<main>";
     /* =================================================================================================================================
                             CONTENIDO
        =================================================================================================================================*/
    if (isset($_GET["ruta"])) {

      if ($_GET["ruta"]=="salir") {
  
        include "modulos/salir.php";

      }
      elseif($_GET["ruta"]=="alistar"){

        include "modulos/alistar.php";

      }else{
        include "modulos/404.php";
      }

    }elseif($_SESSION["usuario"]["perfil"]===3){
      print($_SESSION["usuario"]["perfil"]);
        // include "modulos/alistar.php";
      
    }else {
        include "modulos/transportador.php";
    }
    echo "</main>";

    /*============================================================================================================================
                    FOOTER
    ==============================================================================================================================*/
    include "modulos/footer.php";

  } else {

    include "modulos/login.php";
  
  } 
    
  ?>

</body>

</html>