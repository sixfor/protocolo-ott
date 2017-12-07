<div class="row">
  <div class="col-xs-12">
    <div class="row cont-login">
      <div class="wrapper">
        <div class="form-signin">
          <a href="login.php"><img src="public/img/logo.png" alt="logo-ott" class="img-responsive"></a>
          <br>
          <h3 class="form-signin-heading">Recuperar Contraseña <br><small></small></h3>
          <span>Ingresar correo asociado a la Ventanilla Virtual.</span>
          <div class="form-group">
            <label for="email">Correo electrónico:</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="" required="" autofocus="" />
            <div class="line-form">
              <div class="line-one-form"></div>
              <div class="line-two-form"></div>
            </div>
          </div>
          <button class="btn btn-lg btn-primary btn-block" name="password_forget" id="password_forget" type="button" onclick="passwordForget();">Enviar Solicitud</button>
          <div class="row">
            <div class="col-xs-12 text-center">
              <br>
              <a href="login.php">¡Espera! Regresar a la ventanilla principal.</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="public/js/ajax/ajax.js" charset="utf-8"></script>
<script src="public/js/ajax/authentication.js" charset="utf-8"></script>
