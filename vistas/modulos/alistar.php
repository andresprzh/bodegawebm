<!-- ============================================================================================================================
                                                    FORMAULARIO    
============================================================================================================================ -->
<div class="fixed" style="padding-left:15px;" >

    <div class="row">
  
        <select   list="requeridos" name="requeridos" class="requeridos browser-default col s12 " id="requeridos">
            <option value="" disabled selected>Número requisicion</option>
        </select>
            
    </div>

</div>
<!--============================================================================================================================
============================================================================================================================
                                        TABLAS
============================================================================================================================
============================================================================================================================-->

<div class="row " id="contenido"  >
            
    <ul class="tabs col s12 " id="tabsmenu" >
        <li class="tab col s6" id="tabsI1">
            <a class="black-text"  href="#TablaV">
                <i class="fas fa-clipboard-list"></i>
                <span style="font-size:100%"> Items por alistar</span>
            </a>
        </li>
        <li class="tab col s6 " id="tabsI2">
            <a class="black-text"  href="#TablaE">
                <i class="fas fa-box-open"></i>
                <span style="font-size:100%"> Caja en proceso</span>
            </a>
        </li>
    </ul>

    
    <!-- ==============================================================
                            TABLA VISTA O MUESTRA    
    ============================================================== -->
    
    <div  id="TablaV" >
        <div class=" entradas hide ">
            <div class="col s9">
                <select  list="ubicacion" name="ubicacion" class="browser-default " id="ubicacion">
                    <option value=""  selected>Ubicacion</option>
                </select>
            </div> 
            <div class="col s3">
                <button id="refresh" title="Recargar" onclick="recargarItems()" class="btn waves-effect waves-light green darken-3 " >
                    <i class="fas fa-sync"></i>
                </button>
            </div> 
            <!-- INPUT PARA AGREGAR ITEMS -->
            <div class="input-field center col s12  input_barras">

                <input  id="codbarras" type="text" class="validate">
                <label for="codbarras" class="right">Item</label>

            </div> 
        </div>


        <table class="highlight centered hide" id="TablaVi"  style="width:100%" >

            <thead>
            
            <tr class="white-text green darken-3">

                <th>Descripción</th>
                <th>Disponibles</th>
                <th>Solicitados</th>
                <th>Ubicacion</th>
                
            </tr>

            </thead>

            <tbody id="tablavista"></tbody>
            
        </table>  
                
    </div>


    <!-- ==============================================================
                TABLA EDITABLE    
    ============================================================== -->
    <div id="TablaE" class="hide" >

        <br>
        <br>
        <br>
        <br>
        <br>
        <form id="formalistados">
        <table class="striped centered " id="TablaEd"  style="width:100%">
        
            <thead>

            <tr  class="white-text green darken-3" >

                <th>Item</th>
                <th>Solicitados</th>
                <th>Alistados</th>
                <th class='grey-text'><i class='fas fa-times-circle'></i></th>
                

            </tr>

            </thead>

            <tbody id="tablaeditable"></tbody>

        </table> 
        
        <!-- ==================================
                INPUT PARA CERRAR CAJA  
        ================================== -->
        <div class="row  " id="input_cerrar">
    
            <div class="divider green darken-4"></div> 
                    
                <div class="fixed"  style="padding-left:15px;" >

                    <div class="row">

                        <div class="input-field col s4 m6 l4 " >

                            <select   name='caja'  class='carcaja browser-default ' id='caja'>
                                
                                <option selected value='CRT'>Caja carton</option>
                                <option value='CPL'>Caja plastica</option>
                                <option value='CAP'>Canasta plastica</option>
                                <option value='GLN'>Galon</option>
                                <option value='GLA'>Galoneta</option>

                            </select>

                        </div>

                        <div class="input-field center col s4 m6 l4 input_barras">

                            <input  id="peso" type="number" class="validate" required>
                            <label for="peso" class="right">Peso en gr</label>

                        </div> 

                        <div class="input-field col s4 m2 l2">

                            <button id="cerrar" type="submit" class="btn waves-effect tea darken-4 col s12 m12 l8" >
                                Cerrar
                            </button>
                            
                        </div>  
                        
                    </div>

                </div>

            </div>

        </div>
        </form>
            
    </div>


<!-- ============================================================================================================================
                                                    MODAL EDITAR CAJA 
============================================================================================================================ -->
<div id="editaritem" class="modal grey lighten-3 container">
    <!-- <h4 class="center-align">Cantidad de items a alistar</h4> -->
    <p class="center-align" id="modalitem">Item</p>
        <!-- <p class="col s12">codigo barras</p> -->
    <p class="left">Disponibles: <span id="modaldisponible"></span> </p>
    <p class="right">Solicitados: <span id="modalpedidos"></span></p>
    <div class="row">
        
        
        <div class="input-field input_barras col s12">

            <input  id="cantidad" type="number" class="validate">
            <label for="cantidad" >Cantidad a Alistadr</label>

        </div>  
    </div>
</div>  

</div>

<!-- ============================================================================================================================
                                                    SCRIPTS JAVASCRIPT   
============================================================================================================================ -->
<!-- GUARDA EL NOMBRE DEL USUARIO DE LA SESION EN UNA VARIABLE DE JS -->
<script type="text/javascript">
    var id_usuario='<?php echo $_SESSION["usuario"]["id"];?>';
    var perfil='<?php echo $_SESSION["usuario"]["perfil"];?>';
</script>

<!-- JS QUE MANEJA LOS EVENTOS DE LA PAGINA -->
<script src="vistas/js/alistar.js">

</script>


