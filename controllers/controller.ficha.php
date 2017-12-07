<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  switch (isset($_GET["action"]) ? $_GET["action"] : null) {

    case 'registrarme':


      require_once '../ack/ack.php';
      require_once '../models/class.conexion.php';
      require_once '../models/class.user.php';
      require_once '../models/class.consulta.php';
      require_once 'controller.functions.php';
      require_once 'controller.password.create.php';
      require_once 'controller.filtro.php';
      require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
      require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
      require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';

      /**
       * Arreglo con mensajes de error.
       *
       * empty   => Un campo del formulario fue dejado en blanco
       * isset   => Un campo del formulario no fue envíado o no existe
       * filter  => El formulario no ha pasado el filtro
       * exists_email => El email ya existe
       */
      // $error = array(
      //   'empty'  => '<script> toastr.warning("Debe completar todos los campos para continuar con la suscripción.", "Lo sentimos."); </script>',
      //   'isset'  => '<script> toastr.error("Ha ocurrido un error inesperado. Intentelo más tarde.", "¡Ups!"); </script>',
      //   'filter' => '<script> toastr.error("Ha ocurrido un error al procesar el formulario, intentelo nuevamente.", "¡Alto ahí!"); </script>',
      //   'exists_email' => '<script> toastr.warning("El email o el RUT que esta intentado registrar ya existe.", "Lo sentimos."); </script>',
      //   'no_user' => '<script> toastr.warning("No se ha podido ingresar al usuario.", "Lo sentimos."); </script>',
      //   'no_ficha' => '<script> toastr.error("No se ha podido procesar la ficha de suscripción.", "Lo sentimos."); </script>',
      //   'success' => '<script> toastr.success("Se ha enviado un correo electrónico al usuario para crear la clave secreta. Revisar bandeja de entrada, correo no deseado o SPAM.", "Muy bien."); </script>',
      //   'rut_invalido' => '<script> toastr.warning("Asegurece de que el RUT sea válido e intentelo nuevamente.", "Un momento."); </script>',
      //   'no_mail' => '<script> toastr.warning("Ha ocurrido un problema al tratar de enviar el correo electrónico", "Lo sentimos.");</script>'
      // );

      if (isset($_POST["nombre_contacto"])  && isset($_POST["email_contacto"]) &&
          isset($_POST["razon_social"])     && isset($_POST["rut"])            &&
          isset($_POST["direccion"])        && isset($_POST["comuna"])         &&
          isset($_POST["celular"])          && isset($_POST["apellido_contacto"])) {

        if (!empty($_POST["nombre_contacto"])  && !empty($_POST["email_contacto"]) &&
            !empty($_POST["razon_social"])     && !empty($_POST["rut"])            &&
            !empty($_POST["direccion"])        && !empty($_POST["comuna"])         &&
            !empty($_POST["celular"])          && !empty($_POST["apellido_contacto"])) {

          # Si todos los campos existen, se deben filtrar
          $nombre_contacto   = limpiar($_POST["nombre_contacto"]);
          $apellido_contacto = limpiar($_POST["apellido_contacto"]);
          $email_contacto    = limpiar($_POST["email_contacto"]);
          $razon_social      = limpiar($_POST["razon_social"]);
          $rut               = limpiar($_POST["rut"]);
          $act_productiva    = limpiar($_POST["act_productiva"]);
          $direccion         = limpiar($_POST["direccion"]);
          $comuna            = limpiar($_POST["comuna"]);
          $telefono          = limpiar($_POST["telefono"]);
          $celular           = limpiar($_POST["celular"]);
          $email             = limpiar($_POST["email_empresa"]);
          $email_opcional    = limpiar($_POST["email_opcional"]);

          if ($nombre_contacto   == strtolower($_POST["nombre_contacto"])   &&
              $apellido_contacto == strtolower($_POST["apellido_contacto"]) &&
              $email_contacto    == strtolower($_POST["email_contacto"])    &&
              $razon_social      == strtolower($_POST["razon_social"])      &&
              $rut               == strtolower($_POST["rut"])               &&
              $act_productiva    == strtolower($_POST["act_productiva"])    &&
              $direccion         == strtolower($_POST["direccion"])         &&
              $comuna            == strtolower($_POST["comuna"])            &&
              $telefono          == strtolower($_POST["telefono"])          &&
              $celular           == strtolower($_POST["celular"])           &&
              $email             == strtolower($_POST["email_empresa"])     &&
              $email_opcional    == strtolower($_POST["email_opcional"])) {

            if (validaRut($_POST["rut"])) {

              $user = new Usuario();

              # Seteamos los parametros obligatorios en la clase Usuario
              $user->setEmail(trim(strtolower($_POST["email_contacto"])));
              $user->setNombre(trim($_POST["nombre_contacto"]));
              $user->setApellido(trim($_POST["apellido_contacto"]));
              $user->setRut(trim(str_replace(".", "", $_POST["rut"])));
              $user->setRazonSocial(trim($_POST["razon_social"]));
              $user->setDireccion(trim($_POST["direccion"]));
              $user->setComuna(trim($_POST["comuna"]));
              $user->setTelefono(trim($_POST["telefono"]));
              $user->setCelular(trim($_POST["celular"]));
              $user->setEmailEmpresa(!empty($_POST["email_empresa"]) ? trim(strtolower($_POST["email_empresa"])) : trim(strtolower($_POST["email_contacto"])));
              $user->setSectorEconomico(trim($_POST["act_productiva"]));
              $user->setEmailOpcional(trim(strtolower($_POST["email_opcional"])));
              # Setamos al usuario como normal (2)
              $user->setIdRol(2);
              # Seteamos al usuario como activado (1)
              $user->setEstado(1);

              # Crea una contraseña aleatoria
              // $defaultPassword = encriptar(explode("-", trim($_POST["rut"])), KEY_ENCRYPT_ALL);
              $defaultPassword = encriptar(rand(1000, getrandmax()), KEY_ENCRYPT);
              $user->setPassword($defaultPassword);
              # Insertamos el usuario
              $insertUser = $user->addUser();
              // print_r($insertUser);

              # ERROR => 1062 | Hace referencia a una clave duplicada. El email es UNIQUE
              # y no puede haber otro igual en la tabla tbl_uzuaryoz
              if ($insertUser === 1062) {
                echo 'exists_email';
              }
              # Si entra en este 'else if' el usuario se ha insertado correctamente
              else if ($insertUser === true) {

                # Seteamos la localización por defecto
                date_default_timezone_set('America/Santiago');

                echo 'success';
                # Crea el token y lo guarda en la base de datos
                $token = createTokenPassword($defaultPassword, $user->getEmail());
                # Email encriptado para la URL
                $emailUrl = encriptar($user->getEmail(), KEY_ENCRYPT_ALL);
                # Fecha de caducidad
                $fechaHoy = date('Y-m-d H:i:s');
                # Se le sumaran 24 Horas a la fecha
                $fechaCaducidad = strtotime('+24 hour', strtotime($fechaHoy));
                $fechaUrl = encriptar(date('Y-m-d H:i:s', $fechaCaducidad) , KEY_ENCRYPT_ALL);
                # Envía el mail para que el usuario cree su contraseña
                $mail = new PHPMailer(true);

                try {
                  // $mail->SMTPDebug = 3;
                  $mail->isSMTP();
                  $mail->Host = HOST_EMAIL;
                  $mail->SMTPAuth = true;
                  $mail->Username = SERVER_EMAIL;
                  $mail->Password = SERVER_EMAIL_PASSWORD;
                  $mail->SMTPSecure = SMTP_SECURE;
                  $mail->Port = SMTP_PORT;

                  $mail->setFrom(SERVER_EMAIL, 'Ventanilla OTT');
                  $mail->addAddress($user->getEmail(), $user->getNombre());

                  if (!empty($user->getEmailOpcional()))
                    $mail->addCC($user->getEmailOpcional());

                  $mail->isHTML(true);

                  $mail->Subject = 'Crear clave secreta - Ventanilla OTT';
                  # URL para la etiqueta <a>
                  $urlCreate = 'http://www.ventanilla.ott.cl/login.php?r=password_create&email='.$emailUrl.'&token='.$token.'&expdate='.$fechaUrl;
                  $urlPasswordForget = 'http://www.ventanilla.ott.cl/login.php?r=password_forget';
                  $body = '<html>
                            <head>
                              <style>
                                table {
                                  font-family: "Arial";
                                }
                                th {
                                  height: 100px;
                                  background-color: #00b594;
                                  width: 100%;
                                }
                                h1 {
                                  font-size: 22px;
                                  margin: 0;
                                  color: white;
                                  padding: 20px 10px 20px 10px;
                                }
                                h3 {
                                  font-size: 16px;
                                  font-weight: 100;
                                }
                                h4 {
                                  font-size: 15px;
                                }
                                p {
                                  font-size: 13px;
                                }
                                a {
                                  color: #00b594;
                                }
                                small {
                                  font-size: 12px;
                                }
                                strong {
                                  color: red;
                                }
                              </style>
                            </head>
                            <body>
                              <table>
                                <thead>
                                  <th><h1>Bienvenido a la Ventanilla Virtual</th>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><h3>Te damos la bievenida a la Ventanilla Virtual '.ucfirst($user->getNombre()).' '.ucfirst($user->getApellido()).'.</h3></td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <p>
                                        La Oficina de Transferencia Tecnol&oacute;gica le da la bienvenida y le informa que para
                                        comenzar a utilizar la ventanilla virtual necesitar&aacute; <a href="'.$urlCreate.'">crear su contrase&ntilde;a.</a>
                                        Usted tiene un plazo de <strong>24 horas</strong> para cambiar su contraseña, si no lo hace tendrá que solicitar un nuevo <a href="'.$urlPasswordForget.'">cambio de contraseña</a>.
                                        <br>
                                        Gracias por registrarse en la Ventanilla Virtual.
                                      </p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <p>Haga click en el siguiente enlace para crear su contrase&ntilde;a:</p>
                                      <a href="'.$urlCreate.'">'.$urlCreate.'</a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <br>
                                      <small>Si ha recibido este correo por error, por favor ign&oacute;relo y b&oacute;rrelo de su bandeja de entrada. Lo sentimos.</small>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </body>
                          </html>';
                  $mail->Body = $body;

                  if (!$mail->send()) {
                    // echo $mail->ErrorInfo;
                    echo 'no_mail';
                  }
                }
                catch(Exception $e) {
                  echo 'no_mail';
                }
              }
              else {
                echo 'no_user';
              }
            }
            # Rut invalido
            else {
              echo 'rut_invalido';
            }
          }
          else {
            # Posible Inyeccion SQL
          echo 'filter';
          }
        }
        else {
          echo 'empty';
        }
      }
      else {
        # Mostramos un mensaje de tipo DANGER ya que el DOM ha sido alterado
        echo 'isset';
      }
      break;

    case 'registrar_usuario':

      require_once '../ack/ack.php';
      require_once '../models/class.conexion.php';
      require_once '../models/class.user.php';
      require_once '../models/class.consulta.php';
      require_once 'controller.functions.php';
      require_once 'controller.password.create.php';
      require_once 'controller.filtro.php';
      require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
      require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
      require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';

      /**
       * Arreglo con mensajes de error.
       *
       * empty   => Un campo del formulario fue dejado en blanco
       * isset   => Un campo del formulario no fue envíado o no existe
       * filter  => El formulario no ha pasado el filtro
       * exists_email => El email ya existe
       */
      // $error = array(
      //   'empty'  => '<script> toastr.warning("Debe completar todos los campos para continuar con la suscripción.", "Lo sentimos."); </script>',
      //   'isset'  => '<script> toastr.error("Ha ocurrido un error inesperado. Intentelo más tarde.", "¡Ups!"); </script>',
      //   'filter' => '<script> toastr.error("Ha ocurrido un error al procesar el formulario, intentelo nuevamente.", "¡Alto ahí!"); </script>',
      //   'exists_email' => '<script> toastr.warning("El email o el RUT que esta intentado registrar ya existe.", "Lo sentimos."); </script>',
      //   'no_user' => '<script> toastr.warning("No se ha podido ingresar al usuario.", "Lo sentimos."); </script>',
      //   'no_ficha' => '<script> toastr.error("No se ha podido procesar la ficha de suscripción.", "Lo sentimos."); </script>',
      //   'success' => '<script> toastr.success("Se ha enviado un correo electrónico al usuario para crear la clave secreta. Revisar bandeja de entrada, correo no deseado o SPAM.", "Muy bien."); </script>',
      //   'rut_invalido' => '<script> toastr.warning("Asegurece de que el RUT sea válido e intentelo nuevamente.", "Un momento."); </script>',
      //   'no_mail' => '<script> toastr.warning("Ha ocurrido un problema al tratar de enviar el correo electrónico", "Lo sentimos.");</script>'
      // );

      if (isset($_POST["password_contacto"]) && isset($_POST["nombre_contacto"])  && isset($_POST["email_contacto"]) &&
          isset($_POST["razon_social"])     && isset($_POST["rut"])            &&
          isset($_POST["direccion"])        && isset($_POST["comuna"])         &&
          isset($_POST["celular"])          && isset($_POST["apellido_contacto"])) {

        if (!empty($_POST["password_contacto"]) && !empty($_POST["nombre_contacto"])  && !empty($_POST["email_contacto"]) &&
            !empty($_POST["razon_social"])     && !empty($_POST["rut"])            &&
            !empty($_POST["direccion"])        && !empty($_POST["comuna"])         &&
            !empty($_POST["celular"])          && !empty($_POST["apellido_contacto"])) {

          # Si todos los campos existen, se deben filtrar
          $password_contacto = limpiar($_POST["password_contacto"]);
          $nombre_contacto   = limpiar($_POST["nombre_contacto"]);
          $apellido_contacto = limpiar($_POST["apellido_contacto"]);
          $email_contacto    = limpiar($_POST["email_contacto"]);
          $razon_social      = limpiar($_POST["razon_social"]);
          $rut               = limpiar($_POST["rut"]);
          $act_productiva    = limpiar($_POST["act_productiva"]);
          $direccion         = limpiar($_POST["direccion"]);
          $comuna            = limpiar($_POST["comuna"]);
          $telefono          = limpiar($_POST["telefono"]);
          $celular           = limpiar($_POST["celular"]);
          $email             = limpiar($_POST["email_empresa"]);
          $email_opcional    = limpiar($_POST["email_opcional"]);

          if ($password_contacto == strtolower($_POST["password_contacto"]) &&
              $nombre_contacto   == strtolower($_POST["nombre_contacto"])   &&
              $apellido_contacto == strtolower($_POST["apellido_contacto"]) &&
              $email_contacto    == strtolower($_POST["email_contacto"])    &&
              $razon_social      == strtolower($_POST["razon_social"])      &&
              $rut               == strtolower($_POST["rut"])               &&
              $act_productiva    == strtolower($_POST["act_productiva"])    &&
              $direccion         == strtolower($_POST["direccion"])         &&
              $comuna            == strtolower($_POST["comuna"])            &&
              $telefono          == strtolower($_POST["telefono"])          &&
              $celular           == strtolower($_POST["celular"])           &&
              $email             == strtolower($_POST["email_empresa"])     &&
              $email_opcional    == strtolower($_POST["email_opcional"])) {

            if (validaRut($_POST["rut"])) {

              $user = new Usuario();

              # Seteamos los parametros obligatorios en la clase Usuario
              $user->setEmail(trim(strtolower($_POST["email_contacto"])));
              $user->setNombre(trim($_POST["nombre_contacto"]));
              $user->setApellido(trim($_POST["apellido_contacto"]));
              $user->setRut(trim(str_replace(".", "", $_POST["rut"])));
              $user->setRazonSocial(trim($_POST["razon_social"]));
              $user->setDireccion(trim($_POST["direccion"]));
              $user->setComuna(trim($_POST["comuna"]));
              $user->setTelefono(trim($_POST["telefono"]));
              $user->setCelular(trim($_POST["celular"]));
              $user->setEmailEmpresa(!empty($_POST["email_empresa"]) ? trim(strtolower($_POST["email_empresa"])) : trim(strtolower($_POST["email_contacto"])));
              $user->setSectorEconomico(trim($_POST["act_productiva"]));
              $user->setEmailOpcional(trim(strtolower($_POST["email_opcional"])));
              # Setamos al usuario como normal (2)
              $user->setIdRol(2);
              # Seteamos al usuario como activado (1)
              $user->setEstado(1);


              $user->setPassword(encriptar($_POST["password_contacto"], KEY_ENCRYPT));
              # Insertamos el usuario
              $insertUser = $user->addUser();
              // print_r($insertUser);

              # ERROR => 1062 | Hace referencia a una clave duplicada. El email es UNIQUE
              # y no puede haber otro igual en la tabla tbl_uzuaryoz
              if ($insertUser === 1062) {
                echo 'exists_email';
              }
              # Si entra en este 'else if' el usuario se ha insertado correctamente
              else if ($insertUser === true) {

                # Seteamos la localización por defecto
                date_default_timezone_set('America/Santiago');

                echo 'success';
                # Crea el token y lo guarda en la base de datos
                $token = createTokenPassword($defaultPassword, $user->getEmail());
                # Email encriptado para la URL
                $emailUrl = encriptar($user->getEmail(), KEY_ENCRYPT_ALL);
                # Fecha de caducidad
                $fechaHoy = date('Y-m-d H:i:s');
                # Se le sumaran 24 Horas a la fecha
                $fechaCaducidad = strtotime('+24 hour', strtotime($fechaHoy));
                $fechaUrl = encriptar(date('Y-m-d H:i:s', $fechaCaducidad) , KEY_ENCRYPT_ALL);
                # Envía el mail para que el usuario cree su contraseña
                $mail = new PHPMailer(true);

                try {
                  // $mail->SMTPDebug = 3;
                  $mail->isSMTP();
                  $mail->Host = HOST_EMAIL;
                  $mail->SMTPAuth = true;
                  $mail->Username = SERVER_EMAIL;
                  $mail->Password = SERVER_EMAIL_PASSWORD;
                  $mail->SMTPSecure = SMTP_SECURE;
                  $mail->Port = SMTP_PORT;

                  $mail->setFrom(SERVER_EMAIL, 'Ventanilla OTT');
                  $mail->addAddress($user->getEmail(), $user->getNombre());

                  if (!empty($user->getEmailOpcional()))
                    $mail->addCC($user->getEmailOpcional());

                  $mail->isHTML(true);

                  $mail->Subject = 'Crear clave secreta - Ventanilla OTT';
                  # URL para la etiqueta <a>
                  $urlCreate = 'http://www.ventanilla.ott.cl/login.php?r=password_create&email='.$emailUrl.'&token='.$token.'&expdate='.$fechaUrl;
                  $urlPasswordForget = 'http://www.ventanilla.ott.cl/login.php?r=password_forget';
                  $body = '<html>
                            <head>
                              <style>
                                table {
                                  font-family: "Arial";
                                }
                                th {
                                  height: 100px;
                                  background-color: #00b594;
                                  width: 100%;
                                }
                                h1 {
                                  font-size: 22px;
                                  margin: 0;
                                  color: white;
                                  padding: 20px 10px 20px 10px;
                                }
                                h3 {
                                  font-size: 16px;
                                  font-weight: 100;
                                }
                                h4 {
                                  font-size: 15px;
                                }
                                p {
                                  font-size: 13px;
                                }
                                a {
                                  color: #00b594;
                                }
                                small {
                                  font-size: 12px;
                                }
                                strong {
                                  color: red;
                                }
                              </style>
                            </head>
                            <body>
                              <table>
                                <thead>
                                  <th><h1>Bienvenido a la Ventanilla Virtual</th>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><h3>Te damos la bievenida a la Ventanilla Virtual '.ucfirst($user->getNombre()).' '.ucfirst($user->getApellido()).'.</h3></td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <p>
                                        La Oficina de Transferencia Tecnol&oacute;gica le da la bienvenida y le informa que para
                                        comenzar a utilizar la ventanilla virtual necesitar&aacute; <a href="'.$urlCreate.'">crear su contrase&ntilde;a.</a>
                                        Usted tiene un plazo de <strong>24 horas</strong> para cambiar su contraseña, si no lo hace tendrá que solicitar un nuevo <a href="'.$urlPasswordForget.'">cambio de contraseña</a>.
                                        <br>
                                        Gracias por registrarse en la Ventanilla Virtual.
                                      </p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <p>Haga click en el siguiente enlace para crear su contrase&ntilde;a:</p>
                                      <a href="'.$urlCreate.'">'.$urlCreate.'</a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <br>
                                      <small>Si ha recibido este correo por error, por favor ign&oacute;relo y b&oacute;rrelo de su bandeja de entrada. Lo sentimos.</small>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </body>
                          </html>';
                  $mail->Body = $body;

                  if (!$mail->send()) {
                    // echo $mail->ErrorInfo;
                    echo 'no_mail';
                  }
                }
                catch(Exception $e) {
                  echo 'no_mail';
                }
              }
              else {
                echo 'no_user';
              }
            }
            # Rut invalido
            else {
              echo 'rut_invalido';
            }
          }
          else {
            # Posible Inyeccion SQL
          echo 'filter';
          }
        }
        else {
          echo 'empty';
        }
      }
      else {
        # Mostramos un mensaje de tipo DANGER ya que el DOM ha sido alterado
        echo 'isset';
      }
      break;

    case 'modificar_usuario':

      require_once '../ack/ack.php';
      require_once '../models/class.conexion.php';
      require_once '../models/class.user.php';
      require_once '../models/class.consulta.php';
      require_once 'controller.functions.php';
      require_once 'controller.password.create.php';
      require_once 'controller.filtro.php';

      require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
      require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
      require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';


      session_start();
      /**
       * Arreglo con mensajes de error.
       *
       * empty   => Un campo del formulario fue dejado en blanco
       * isset   => Un campo del formulario no fue envíado o no existe
       * filter  => El formulario no ha pasado el filtro
       * exists_email => El email ya existe
       */
      // $error = array(
      //   'empty'  => '<script> toastr.warning("Debe completar todos los campos para continuar con la suscripción.", "Lo sentimos."); </script>',
      //   'isset'  => '<script> toastr.error("Ha ocurrido un error inesperado. Intentelo más tarde.", "¡Ups!"); </script>',
      //   'filter' => '<script> toastr.error("Ha ocurrido un error al procesar el formulario, intentelo nuevamente.", "¡Alto ahí!"); </script>',
      //   'exists_email' => '<script> toastr.warning("El email o el RUT que esta intentado registrar ya existe.", "Lo sentimos."); </script>',
      //   'no_user' => '<script> toastr.warning("No se ha podido ingresar al usuario.", "Lo sentimos."); </script>',
      //   'no_ficha' => '<script> toastr.error("No se ha podido procesar la ficha de suscripción.", "Lo sentimos."); </script>',
      //   'success' => '<script> toastr.success("Se ha enviado un correo electrónico al usuario para crear la clave secreta. Revisar bandeja de entrada, correo no deseado o SPAM.", "Muy bien."); </script>',
      //   'rut_invalido' => '<script> toastr.warning("Asegurece de que el RUT sea válido e intentelo nuevamente.", "Un momento."); </script>',
      //   'no_mail' => '<script> toastr.warning("Ha ocurrido un problema al tratar de enviar el correo electrónico", "Lo sentimos.");</script>'
      // );

      if (isset($_POST["password_contacto"]) && isset($_POST["usuario"]) && isset($_POST["nombre_contacto"])  && isset($_POST["email_contacto"]) &&
          isset($_POST["razon_social"])     && isset($_POST["rut"])            &&
          isset($_POST["direccion"])        && isset($_POST["comuna"])         &&
          isset($_POST["celular"])          && isset($_POST["apellido_contacto"])) {

        if (!empty($_POST["usuario"]) && !empty($_POST["nombre_contacto"])  && !empty($_POST["email_contacto"]) &&
            !empty($_POST["razon_social"])     && !empty($_POST["rut"])            &&
            !empty($_POST["direccion"])        && !empty($_POST["comuna"])         &&
            !empty($_POST["celular"])          && !empty($_POST["apellido_contacto"])) {

          # Si todos los campos existen, se deben filtrar
          $password          = limpiar($_POST["password_contacto"]);
          $usuario           = limpiar($_POST["usuario"]);
          $nombre_contacto   = limpiar($_POST["nombre_contacto"]);
          $apellido_contacto = limpiar($_POST["apellido_contacto"]);
          $email_contacto    = limpiar($_POST["email_contacto"]);
          $razon_social      = limpiar($_POST["razon_social"]);
          $rut               = limpiar($_POST["rut"]);
          $act_productiva    = limpiar($_POST["act_productiva"]);
          $direccion         = limpiar($_POST["direccion"]);
          $comuna            = limpiar($_POST["comuna"]);
          $telefono          = limpiar($_POST["telefono"]);
          $celular           = limpiar($_POST["celular"]);
          $email             = limpiar($_POST["email_empresa"]);
          $email_opcional    = limpiar($_POST["email_opcional"]);

          if ($password          == strtolower($_POST["password_contacto"]) &&
              $usuario           == strtolower($_POST["usuario"])           &&
              $nombre_contacto   == strtolower($_POST["nombre_contacto"])   &&
              $apellido_contacto == strtolower($_POST["apellido_contacto"]) &&
              $email_contacto    == strtolower($_POST["email_contacto"])    &&
              $razon_social      == strtolower($_POST["razon_social"])      &&
              $rut               == strtolower($_POST["rut"])               &&
              $act_productiva    == strtolower($_POST["act_productiva"])    &&
              $direccion         == strtolower($_POST["direccion"])         &&
              $comuna            == strtolower($_POST["comuna"])            &&
              $telefono          == strtolower($_POST["telefono"])          &&
              $celular           == strtolower($_POST["celular"])           &&
              $email             == strtolower($_POST["email_empresa"])     &&
              $email_opcional    == strtolower($_POST["email_opcional"])) {

            if (validaRut($_POST["rut"])) {

              $user = new Usuario();

              # Seteamos los parametros obligatorios en la clase Usuario
              $user->setPassword( !empty($_POST["password_contacto"]) ? encriptar($_POST["password_contacto"], KEY_ENCRYPT) : "" );
              $user->setId($_POST["usuario"]);
              $user->setEmail(trim(strtolower($_POST["email_contacto"])));
              $user->setNombre(trim($_POST["nombre_contacto"]));
              $user->setApellido(trim($_POST["apellido_contacto"]));
              $user->setRut(trim(str_replace(".", "", $_POST["rut"])));
              $user->setRazonSocial(trim($_POST["razon_social"]));
              $user->setDireccion(trim($_POST["direccion"]));
              $user->setComuna(trim($_POST["comuna"]));
              $user->setTelefono(trim($_POST["telefono"]));
              $user->setCelular(trim($_POST["celular"]));
              $user->setEmailEmpresa(!empty($_POST["email_empresa"]) ? trim(strtolower($_POST["email_empresa"])) : trim(strtolower($_POST["email_contacto"])));
              $user->setSectorEconomico(trim($_POST["act_productiva"]));
              $user->setEmailOpcional(trim(strtolower($_POST["email_opcional"])));
              # Setamos al usuario como normal (2)
              $user->setIdRol(2);
              # Seteamos al usuario como activado (1)
              $user->setEstado(1);

              # Insertamos el usuario
              $updateUser = $user->updateUser($_SESSION["user_ott"][0]["id_rol"]);
              // print_r($updateUser);

              # ERROR => 1062 | Hace referencia a una clave duplicada. El email es UNIQUE
              # y no puede haber otro igual en la tabla tbl_uzuaryoz
              if ($updateUser === 1062) {
                echo 'exists_email';
              }
              # Si entra en este 'else if' el usuario se ha insertado correctamente
              else if ($updateUser === true) {

                # Seteamos la localización por defecto
                date_default_timezone_set('America/Santiago');

                echo 'success';
                # Crea el token y lo guarda en la base de datos
                $token = createTokenPassword($defaultPassword, $user->getEmail());
                # Email encriptado para la URL
                $emailUrl = encriptar($user->getEmail(), KEY_ENCRYPT_ALL);
                # Fecha de caducidad
                $fechaHoy = date('Y-m-d H:i:s');
                # Se le sumaran 24 Horas a la fecha
                $fechaCaducidad = strtotime('+24 hour', strtotime($fechaHoy));
                $fechaUrl = encriptar(date('Y-m-d H:i:s', $fechaCaducidad) , KEY_ENCRYPT_ALL);
                # Envía el mail para que el usuario cree su contraseña
                $mail = new PHPMailer(true);

                try {
                  // $mail->SMTPDebug = 3;
                  $mail->isSMTP();
                  $mail->Host = HOST_EMAIL;
                  $mail->SMTPAuth = true;
                  $mail->Username = SERVER_EMAIL;
                  $mail->Password = SERVER_EMAIL_PASSWORD;
                  $mail->SMTPSecure = SMTP_SECURE;
                  $mail->Port = SMTP_PORT;
                  $mail->CharSet = 'UTF-8';

                  $mail->setFrom(SERVER_EMAIL, 'Ventanilla OTT');
                  $mail->addAddress($user->getEmail(), $user->getNombre());

                  $mail->addCC(EMAIL_OTT, 'Ventanilla OTT');

                  if (!empty($user->getEmailOpcional()))
                    $mail->addCC($user->getEmailOpcional());

                  $mail->isHTML(true);

                  $mail->Subject = 'Cuenta Modificada - Ventanilla OTT';

                  $body = '<html>
                            <head>
                              <style>
                                table {
                                  font-family: "Arial";
                                }
                                th {
                                  height: 100px;
                                  background-color: #00b594;
                                  width: 100%;
                                }
                                h1 {
                                  font-size: 22px;
                                  margin: 0;
                                  color: white;
                                  padding: 20px 10px 20px 10px;
                                }
                                h3 {
                                  font-size: 16px;
                                  font-weight: 100;
                                }
                                h4 {
                                  font-size: 15px;
                                }
                                p {
                                  font-size: 13px;
                                }
                                a {
                                  color: #00b594;
                                }
                                small {
                                  font-size: 12px;
                                }
                                strong {
                                  color: red;
                                }
                              </style>
                            </head>
                            <body>
                              <table>
                                <thead>
                                  <th><h1>Ventanilla Virtual</th>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><h3>'.ucfirst($user->getNombre()).' '.ucfirst($user->getApellido()).'.</h3></td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <p>
                                        Te notificamos que tu cuenta ha sido modificada por un administrador de la Ventanilla Virtual <br>
                                        '.( !empty($_POST["password_contacto"]) ? 'Tu nueva clave es: '. $_POST["password_contacto"] : "" ).' 
                                      </p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <br>
                                      <small>Si ha recibido este correo por error, por favor ign&oacute;relo y b&oacute;rrelo de su bandeja de entrada. Lo sentimos.</small>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </body>
                          </html>';
                  $mail->Body = $body;

                  if (!$mail->send()) {
                    // echo $mail->ErrorInfo;
                    echo 'no_mail';
                  }
                }
                catch(Exception $e) {
                  echo 'no_mail';
                }
              }
              else {
                echo 'no_user';
              }
            }
            # Rut invalido
            else {
              echo 'rut_invalido';
            }
          }
          else {
            # Posible Inyeccion SQL
          echo 'filter';
          }
        }
        else {
          echo 'empty';
        }
      }
      else {
        # Mostramos un mensaje de tipo DANGER ya que el DOM ha sido alterado
        echo 'isset';
      }
      break;

    case 'delete_usuario':

      require_once '../ack/ack.php';
      require_once '../models/class.conexion.php';
      require_once '../models/class.user.php';
      require_once '../models/class.consulta.php';
      require_once '../models/class.accion.php';
      require_once 'controller.functions.php';
      require_once 'controller.password.create.php';
      require_once 'controller.filtro.php';
      require_once 'controller.accion.php';

      require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
      require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
      require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';

      session_start();

      if (isset($_POST["usuario"]) && !empty($_POST["usuario"])) {

        $usuario = limpiar($_POST["usuario"]);

        if ($usuario == strtolower($_POST["usuario"])) {

          $objUsuario = new Usuario();
          $objUsuario->setId($_POST["usuario"]);
          $deleteUser = $objUsuario->deleteUser($_SESSION["user_ott"][0]["id_rol"]);

          if ($deleteUser) {

            $usuarios = $objUsuario->getUsers($_SESSION["user_ott"][0]["id_rol"]);
            $acciones = getAccionesOnModulo($_SESSION["user_ott"][0]["id_rol"], 3);

            echo "1|";
            foreach ($usuarios as $key => $value) {
              ?>
              <tr>
                <td class="text-capitalize"><?php echo $value["nombre"].' '.$value["apellido"]; ?></td>
                <td><?php echo $value["email"]; ?></td>
                <td><?php echo $value["rut"]; ?></td>
                <td><?php echo (!empty($value["telefono"]) ? '<i class="fa fa-phone" aria-hidden="true"></i> '. $value["telefono"] : null). (!empty($value["celular"]) ? ' <i class="fa fa-mobile" aria-hidden="true"></i> '.$value["celular"] : null); ?></td>
                <td>
                  <?php
                    foreach ($acciones as $keyAccion => $valueAccion) {
                      if ($valueAccion["id_accion"] == 12) {
                        echo '<a href="?m='.$valueAccion["id_modulo"].'&a='.$valueAccion["id_accion"].'&u='.$value["id_usuario"].'">' . $valueAccion["icono"].'</a> &nbsp;&nbsp;';
                      }
                      else if ($valueAccion["id_accion"] == 14) {
                        echo '<a href="#" onclick="deleteUser('.$value['id_usuario'].')"> '.$valueAccion["icono"].'</a>';
                      }
                    }
                  ?>
                </td>
              </tr>
              <?php
            }

            $arrayUsuario = $objUsuario->getUser($_SESSION["user_ott"][0]["id_rol"]);

            $mail = new PHPMailer(true);

            try {
              // $mail->SMTPDebug = 3;
              $mail->isSMTP();
              $mail->Host = HOST_EMAIL;
              $mail->SMTPAuth = true;
              $mail->Username = SERVER_EMAIL;
              $mail->Password = SERVER_EMAIL_PASSWORD;
              $mail->SMTPSecure = SMTP_SECURE;
              $mail->Port = SMTP_PORT;

              $mail->setFrom(SERVER_EMAIL, 'Ventanilla OTT');
              $mail->addAddress($arrayUsuario[0]["email"], ucfirst($arrayUsuario[0]["nombre"]) . ' ' .ucfirst($arrayUsuario[0]["apellido"]));

              $mail->addCC(EMAIL_OTT, 'Ventanilla OTT');

              if (!empty($arrayUsuario[0]["email_opcional"]))
                $mail->addCC($arrayUsuario[0]["email_opcional"]);

              $mail->isHTML(true);

              $mail->Subject = 'Cuenta Eliminada - Ventanilla OTT';
              # URL para la etiqueta <a>

              $body = '<html>
                        <head>
                          <style>
                            table {
                              font-family: "Arial";
                            }
                            th {
                              height: 100px;
                              background-color: #00b594;
                              width: 100%;
                            }
                            h1 {
                              font-size: 22px;
                              margin: 0;
                              color: white;
                              padding: 20px 10px 20px 10px;
                            }
                            h3 {
                              font-size: 16px;
                              font-weight: 100;
                            }
                            h4 {
                              font-size: 15px;
                            }
                            p {
                              font-size: 13px;
                            }
                            a {
                              color: #00b594;
                            }
                            small {
                              font-size: 12px;
                            }
                            strong {
                              color: red;
                            }
                          </style>
                        </head>
                        <body>
                          <table>
                            <thead>
                              <th><h1>Ventanilla Virtual</th>
                            </thead>
                            <tbody>
                              <tr>
                                <td><h3>Le informamos que su cuenta ha sido eliminada.</h3></td>
                              </tr>
                              <tr>
                                <td>
                                  <p>
                                    La Oficina de Transferencia Tecnol&oacute;gica le informa que su cuenta ha sido dada de baja.
                                  </p>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <br>
                                  <small>Si ha recibido este correo por error, por favor ign&oacute;relo y b&oacute;rrelo de su bandeja de entrada. Lo sentimos.</small>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </body>
                      </html>';
              $mail->Body = $body;

              if (!$mail->send()) {
                // echo $mail->ErrorInfo;
                // echo 'no_mail';
              }
            }
            catch(Exception $e) {
              // echo 'no_mail';
            }

          }
          else {
            echo 2;
          }
        }
        else {
          echo 3;
        }
      }
      else {
        echo 4;
      }

      break;

    case 'buscar_usuario':

      require_once '../ack/ack.php';
      require_once '../models/class.conexion.php';
      require_once '../models/class.user.php';
      require_once '../models/class.consulta.php';
      require_once '../models/class.accion.php';
      require_once 'controller.functions.php';
      require_once 'controller.password.create.php';
      require_once 'controller.filtro.php';
      require_once 'controller.accion.php';

      session_start();

      if (isset($_POST["rut"])) {

        $rut = limpiar($_POST["rut"]);

        if ($rut == strtolower($_POST["rut"])) {

          $objUsuario = new Usuario();
          if (!empty($_POST["rut"])) {
            $objUsuario->setRut(trim(str_replace(".", "", $_POST["rut"])));
            $usuario = $objUsuario->getUserByRut($_SESSION["user_ott"][0]["id_rol"]);
          }
          else {
            $usuario = $objUsuario->getUsers($_SESSION["user_ott"][0]["id_rol"]);
          }
          // var_dump($usuario);
          if (count($usuario) > 0) {
            echo "1|";
            $acciones = getAccionesOnModulo($_SESSION["user_ott"][0]["id_rol"], 3);
            foreach ($usuario as $key => $value) {
              ?>
              <tr>
                <td class="text-capitalize"><?php echo $value["nombre"].' '.$value["apellido"]; ?></td>
                <td><?php echo $value["email"]; ?></td>
                <td><?php echo $value["rut"]; ?></td>
                <td><?php echo (!empty($value["telefono"]) ? '<i class="fa fa-phone" aria-hidden="true"></i> '. $value["telefono"] : null). (!empty($value["celular"]) ? ' <i class="fa fa-mobile" aria-hidden="true"></i> '.$value["celular"] : null); ?></td>
                <td>
                  <?php
                    foreach ($acciones as $keyAccion => $valueAccion) {
                      if ($valueAccion["id_accion"] == 12) {
                        echo '<a href="?m='.$valueAccion["id_modulo"].'&a='.$valueAccion["id_accion"].'&u='.$value["id_usuario"].'">' . $valueAccion["icono"].'</a> &nbsp;&nbsp;';
                      }
                      else if ($valueAccion["id_accion"] == 14) {
                        echo '<a onclick="deleteUser('.$value['id_usuario'].');"> '.$valueAccion["icono"].'</a>';
                      }
                    }
                  ?>
                </td>
              </tr>
              <?php
            }
          }
          else {
            echo 2;
          }
        }
        else {
          echo 3;
        }
      }
      else {
        echo 4;
      }

      break;

    default:
      # code...
      break;
  }

?>
