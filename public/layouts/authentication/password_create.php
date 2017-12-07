<?php

  $token = $_GET["token"];
  $email = descifrar($_GET["email"], KEY_ENCRYPT_ALL);
  $fecha = descifrar($_GET["expdate"], KEY_ENCRYPT_ALL);

  # Valida que el token no esta caducado y que pertenece a email que viene por parametro
  $validarToken = validarTokenCreatePassword($token, $email, $fecha);

  if ($validarToken) {
    ?>
    <div class="row">
      <div class="col-xs-12">
        <div class="row cont-login">
          <div class="wrapper">
            <div class="form-signin">
              <a href="login.php"><img src="public/img/logo.png" alt="logo-ott" class="img-responsive"></a>
              <br>
              <h3 class="form-signin-heading">Crear Contraseña <br><small><?php echo $email; ?></small></h3>
              <small>Usar solo caracteres alfanuméricos (No utilizar espacios).</small>
              <div class="form-group">
                <label for="password">Contraseña nueva</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="" required="" autofocus="" />
                <div class="line-form">
                  <div class="line-one-form"></div>
                  <div class="line-two-form"></div>
                </div>
              </div>
              <div class="form-group">
                <label for="password_repeat">Repetir contraseña</label>
                <input type="password" class="form-control" name="password_repeat" id="password_repeat" required="" autocomplete="off"/>
                <div class="line-form">
                  <div class="line-one-form"></div>
                  <div class="line-two-form"></div>
                </div>
              </div>
              <button class="btn btn-lg btn-primary btn-block" name="password_create" id="password_create" type="button" onclick="crearPassword(<?php echo "'".urlencode($_GET["email"])."', '".urlencode($_GET["token"])."', '".urlencode($_GET["expdate"])."'"; ?>);">CREAR</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
  else {
    ?>
    <div class="row">
      <div class="col-xs-12">
        <div class="row cont-login">
          <div class="wraper">
            <img src="public/img/logo.png" alt="logo-ott" class="img-responsive center-block">
            <br>
            <div class="col-xs-12 col-md-8 col-md-offset-2">
              <h1>Lo sentimos.</h1>
              <p>
                <?php
                  // echo 'asda' . descifrar($_GET["expdate"], KEY_ENCRYPT_ALL);
                ?>
                Su solicitud para cambiar la contraseña ha caducado o ya ha sido utilizada.
                Recuerde que tiene un plazo de <strong>24 horas</strong> para cambiar su contraseña una vez solicitado el cambio. <br><br>
              </p>
              <a href="login.php?r=password_forget" class="btn btn-lg btn-primary pull-right"> Cambiar Contraseña</a>

            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
?>
<script src="public/js/ajax/ajax.js" charset="utf-8"></script>
<script src="public/js/ajax/authentication.js" charset="utf-8"></script>
