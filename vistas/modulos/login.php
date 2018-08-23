  
  
  <main class="login " >
    <center>
      

      <div class=" row">
      
        <div class="z-depth-1 col s10 m6 l4 offset-s1 offset-m3  offset-l4 login-box" >
          <img class="responsive-img" style="width: 200px;" src="vistas\imagenes\plantilla\logo.svg" />     
          <form class="col s12" method="post">
            
            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='text' name='usuario' id='usuario' required />
                <label for='usuario'><i class="fas fa-user"></i> Ingrese Usuario</label>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col  s12'>
                <input class='validate' type='password' name='contraseña' id='contraseña' required />
                <label for='contraseña'><i class="fas fa-key"></i> Contraseña</label>
              </div>
            </div>

            <center>
              <div class='row'>
                <button type='submit' name='btn_login' class='col  s12 btn btn-large waves-effect green'>Ingresar</button>
              </div>
            </center>

              <?php
        
                $login = new ControladorUsuarios();
                $login->ctrIngresoUsuario();

              ?>

          </form>

        </div>

      </div>
     
    </center>
  </main>



