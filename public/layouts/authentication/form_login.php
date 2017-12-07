<div class="row">
  <div class="col-md-12 col-xs-12">
    <div class="row cont-login">
      <div class="col-md-5 col-xs-12 b-r">
        <form action="" method="post" class="form-signin">
          <img src="public/img/logo.png" alt="logo-ott" class="img-responsive">
          <br>
          <h3 class="form-signin-heading">Ventanilla Única</h3>
          <div class="form-group">
            <input type="email" class="form-control" <?php echo isset($_GET["email"]) ? 'value="'.descifrar($_GET["email"], KEY_ENCRYPT_ALL).'"' : null; ?> name="email" placeholder="Ejemplo@gmail.com" required="" <?php echo !isset($_GET["email"]) ? 'autofocus' : null; ?> />
          </div>
          <div class="form-group">
            <input type="password" class="form-control" <?php echo isset($_GET["email"]) ? 'autofocus' : null; ?>  name="password" placeholder="Contraseña" required=""/>
          </div>
          <?php
          if (isset($show_error)) {
          ?>
            <div class="form-group">
              <span class="text-danger"><?php echo $show_error; ?></span>
            </div>
          <?php
          }
          ?>
          <!-- <div class="form-group">
            <span>¿Aún no tienes una cuenta? <strong><a href="?r=register">¡Registrarme!</a></strong></span>
          </div> -->
          <div class="form-group">
            <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">INICIAR SESIÓN</button>
          </div>
          <div class="form-group">
            <span>¿Has olvidado tu contraseña? <strong><a href="?r=password_forget">¡Recuperar Contraseña!</a></strong></span>
          </div>
        </form>
      </div>
      <div class="col-md-7 col-xs-12">
        <div class="row">
          <div class="col-md-12 col-xs-12">
              <a href="?r=register" class="create-account-3">¿Aun no tienes una cuenta? ¡Registrate Aquí!</a>
          </div>
          <div class="col-md-12 col-xs-12 frecuently-question">

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
