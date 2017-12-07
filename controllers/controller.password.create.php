<?php

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  error_reporting(E_ALL);

  switch (isset($_GET["action"]) ? $_GET["action"] : null) {

    case 'password_create':

      if (isset($_POST["password"]) && !empty($_POST["password"]) &&
      isset($_POST["passwordRepeat"]) && !empty($_POST["passwordRepeat"]) &&
      isset($_POST["email"]) && !empty($_POST["email"]) &&
      isset($_POST["token"]) && !empty($_POST["token"]) &&
      isset($_POST["expdate"]) && !empty($_POST["expdate"])) {


        require_once '../ack/ack.php';
        require_once 'controller.filtro.php';
        require_once '../models/class.conexion.php';
        require_once '../models/class.user.php';

        $password = limpiar($_POST["password"]);
        $passwordRepeat = limpiar($_POST["passwordRepeat"]);
        $email = limpiar($_POST["email"]);
        $token = limpiar($_POST["token"]);
        $expdate = limpiar($_POST["expdate"]);

        if ($password == strtolower($_POST["password"]) && $passwordRepeat == strtolower($_POST["passwordRepeat"]) &&
        $email == strtolower($_POST["email"]) && $token == strtolower($_POST["token"]) && $expdate == strtolower($_POST["expdate"])) {

          $password = trim($_POST["password"]);
          $passwordRepeat = trim($_POST["passwordRepeat"]);
          $email = descifrar(trim($_POST["email"]), KEY_ENCRYPT_ALL);
          $token = trim($_POST["token"]);
          $expdate = descifrar(trim($_POST["expdate"]), KEY_ENCRYPT_ALL);

          if ($password == $passwordRepeat) {

            $validarToken = validarTokenCreatePassword($token, $email, $expdate);

            if ($validarToken) {

              $usuario = new Usuario();
              $usuario->setEmail($email);
              $usuario->setPassword(encriptar($password, KEY_ENCRYPT));
              # Cambia la clave
              $changePassword = $usuario->changePassword(3);
              if ($changePassword === true) {

                $usuario->changeStatusTokenPassword(3, 0, $token);

                echo 1;
              }
              # No se cambió la contraseña
              else {
                echo 2;
              }
            }
            # Token inválido
            else {
              echo 3;
            }
          }
          # Contraseñas no coinciden
          else {
            echo 4;
          }
        }
        # Posible Inyección SQL
        else {
          echo 5;
        }
      }
      # Formulario alterado o vacío
      else {
        echo 6;
      }
      break;

    case 'password_forget':

      if (isset($_POST["email"]) && !empty($_POST["email"])) {

        require_once '../ack/ack.php';
        require_once 'controller.filtro.php';
        require_once '../models/class.conexion.php';
        require_once '../models/class.user.php';
        require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
        require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';

        $email = limpiar($_POST["email"]);

        if ($email == strtolower($_POST["email"])) {

          $usuario = new Usuario();
          $email = trim(strtolower($_POST["email"]));
          $usuario->setEmail($email);
          $usuario->setEmailOpcional($email);
          $user = $usuario->getUserByEmail(3);

          if (count($user) > 0) {

            $newToken = solicitarNewPassword($user[0]["password"], $user[0]["email"]);

            if ($newToken) {

              date_default_timezone_set('America/Santiago');
              # Fecha de caducidad
              $fechaHoy = date('Y-m-d H:i:s');
              # Se le sumaran 24 Horas a la fecha
              $fechaCaducidad = strtotime('+24 hour', strtotime($fechaHoy));
              $fechaUrl = encriptar(date('Y-m-d H:i:s', $fechaCaducidad) , KEY_ENCRYPT_ALL);

              $emailUrl = encriptar($user[0]["email"], KEY_ENCRYPT_ALL);

              $url = "http://www.ventanilla.ott.cl/login.php?r=password_create&email=".$emailUrl."&expdate=".$fechaUrl."&token=".$newToken;

              $mail = new PHPMailer(true);

              try {
                $mail->isSMTP();
                $mail->Host = HOST_EMAIL;
                $mail->SMTPAuth = true;
                $mail->Username = SERVER_EMAIL;
                $mail->Password = SERVER_EMAIL_PASSWORD;
                $mail->SMTPSecure = SMTP_SECURE;
                $mail->Port = SMTP_PORT;

                $mail->setFrom(SERVER_EMAIL, 'Ventanilla OTT');
                $mail->addAddress($user[0]["email"], $user[0]["nombre"]);

                if (!empty($user[0]["email_opcional"]))
                  $mail->addCC($user[0]["email_opcional"], $user[0]["nombre"]);

                $mail->isHTML(true);
                $mail->Subject = 'Recuperar clave secreta - Ventanilla OTT';

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
                                  <td><h3>Sr(a): '.ucfirst($user[0]["nombre"]).' '.ucfirst($user[0]["apellido"]).'.</h3></td>
                                </tr>
                                <tr>
                                  <td>
                                    <p>
                                      Usted ha solicitado un cambio de contrase&ntilde;a. <br>
                                      Para hacer efectiva esta operaci&oacute;n usted debera crear una nueva contrase&ntilde;a.
                                      <br>
                                      <p>Haga click en el siguiente enlace para crear su contrase&ntilde;a:</p>
                                      <br>
                                      <a href="'.$url.'">'.$url.'</a>
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <p>Gracias por contactarse con nostros, esperamos haber solucionado su problema.</p>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <br>
                                    <small>Atenci&oacute;n! Si ha recibido este correo por error, por favor ign&oacute;relo y b&oacute;rrelo de su bandeja de entrada. Lo sentimos.</small>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </body>
                        </html>';
                $mail->Body = $body;
                if ($mail->send()) {
                  echo 1;
                }
                # No se envió el correo con la nueva solicitud de contraseña
                else {
                  echo 2;
                }
              }catch(Exception $e) {
                echo 2;
              }
            }
            # No se pudo solicitar el nuevo token. Revisar función solicitarNewPassword();
            else {
              echo 3;
            }
          }
          # El usuario no existe
          else {
            echo 4;
          }
        }
        # Posible Inyección SQL
        else {
          echo 5;
        }
      }
      # Formulario alterado o vacío
      else {
        echo 6;
      }
      break;

    default:
      # code...
      break;
  }



/**
 * Crea un token de la contraseña y lo guarda en la tabla Password Reset junto al
 * email del usuario que se esta registrando.
 *
 * @param Password aleatoria del usuario
 * @param Email del usuario registrado (No el de la empresa)
 */
  function createTokenPassword($password, $email) {

    $usuario = new Usuario();
    $usuario->setEmail($email);
    $token = sha1($password);
    # Parametros ROl, TOKEN, ESTADO
    $createToken = $usuario->createPassword(3, $token, 1);
    # Corre ya existe
    if ($createToken === 1062) {
      return false;
    }

    return $token;
  }

/**
 * Valida que las variables globales que vienen en la URL para crear la contraseña
 * pertenezcan al email que desea cambiar la clave y que no este caducado por fecha y hora
 *
 * @param TOKEN de la contraseña
 * @param EMAIL del usuario que solicita cambio de contraseña
 * @param FECHA de caducidad
 */
  function validarTokenCreatePassword($token, $email, $fecha) {

    date_default_timezone_set('America/Santiago');
    # Si la fecha de Hoy es menor a la fecha de caducidad
    if (date('Y-m-d H:i:s') < $fecha) {
      $usuario = new Usuario();
      $usuario->setEmail($email);

      $user = $usuario->getUserByEmail(3);
      # Si el usuario existe
      if (count($user) > 0) {
        # Parametros ROL, TOKEN
        $resetPassword = $usuario->getResetPassword(3, $token);

        if (count($resetPassword) > 0) {
          # Si el estado es 1 el token aún no se ha usado
          if ($resetPassword[0]["estado"] == 1) {
            return true;
          }
        }
      }
    }
    # Retornara false en caso de que haya caducado el token o si el usuario no existe
    # También si el estado del token es 0
    return false;
  }

  function createNewTokenPassword($password, $email) {

    $usuario = new Usuario();
    $usuario->setEmail($email);
    $token = sha1($password);
    # Parametros ROl, TOKEN, ESTADO
    $changeToken = $usuario->changeTokenPassword(3, $token, 1);

    # Corre ya existe
    if ($changeToken === true) {
      return $token;
    }

    return false;
  }

/**
 * Funcion que solicita un token nuevo para el usuario.
 * El email debe existir en la tabla  Password Reset para poder envíar una solicitud
 * de cambio de contraseña. Si no existe el correo la funcion retornara false
 *
 * @param EMAIL del usuario registrado
 */
  function solicitarNewPassword($password, $email) {

    $usuario = new Usuario();
    $usuario->setEmail($email);
    $arrayResetPassword = $usuario->getResetPasswordByEmail(3);
    $newToken = false;

    if (count($arrayResetPassword) > 0) {

      # Si el token tiene un estado en 1, el token no ha sido utilizado y no hay necesidad de crear otro
      if ($arrayResetPassword[0]["estado"] == 1) {
        $oldToken = $arrayResetPassword[0]["token"];
        return $oldToken;
      }

      # Si el estado del token es 0, el token ya fue utilizado y es necesario crear otro y reemplazar el viejo
      $newToken = createNewTokenPassword($password, $email);
      if ($newToken) {
        return $newToken;
      }
    }
    return $newToken;
  }



?>
