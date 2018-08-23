<div id="contenido-inicio" class="container">
    <!-- <h2 class="header center"  >Aplicación Bodega</h3> -->
</div>

<!-- GUARDA EL NOMBRE DEL USUARIO DE LA SESION EN UNA VARIABLE DE JS -->
<script type="text/javascript">
    var usuario=JSON.parse('<?php print json_encode($_SESSION["usuario"]);?>');
    // console.log(usuario["nombre"]);
</script>

<!-- JS QUE MANEJA LOS EVENTOS DE LA PAGINA -->
<script src="vistas/js/inicio.js">

</script>
    




