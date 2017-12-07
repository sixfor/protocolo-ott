<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  switch (isset($_GET["action"]) ? $_GET["action"] : null) {

    case 'responder_consulta':

      if (isset($_POST["consulta"]) && isset($_POST["usuario"]) && isset($_POST["respuesta"]) && isset($_POST["email"]) && isset($_POST["verificar"])) {
        if (!empty($_POST["consulta"]) && !empty($_POST["usuario"]) && !empty($_POST["respuesta"]) && !empty($_POST["email"])) {

          require_once '../ack/ack.php';
          require_once 'controller.filtro.php';

          $consulta = limpiar($_POST["consulta"]);
          $usuario = limpiar($_POST["usuario"]);
          $respuesta = limpiar($_POST["respuesta"]);
          $email = limpiar($_POST["email"]);
          $verificar = limpiar($_POST["verificar"]);

          if ($consulta == strtolower($_POST["consulta"]) && $usuario == strtolower($_POST["usuario"]) && $respuesta == strtolower($_POST["respuesta"]) && $email == strtolower($_POST["email"]) && $verificar == strtolower($_POST["verificar"])) {

            require_once '../models/class.conexion.php';
            require_once '../models/class.consulta.php';
            require_once '../models/class.consulta.respuesta.php';
            require_once '../models/class.user.php';
            require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
            require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
            require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';

            session_start();
            date_default_timezone_set('America/Santiago');

            $idConsulta = descifrar($_POST["consulta"], KEY_ENCRYPT_ALL);
            $idUsuario = descifrar($_POST["usuario"], KEY_ENCRYPT_ALL);
            $respuesta = trim($_POST["respuesta"]);
            $verificar = trim($_POST["verificar"]);

            # Si el que responde es el ADMIN (ID ROL 3) se marcará como leída y si no, como no leída y se verificará
            $leido = $_SESSION["user_ott"][0]["id_rol"] == 3 ? 1 : 0;
            $objetoConsulta = new Consulta($idConsulta, null, null, null, $verificar, null, $leido, null, null, null);
            $objetoConsulta->leido(3);

            $consultaRespuesta = new ConsultaRespuesta(null, $respuesta, $idConsulta, $idUsuario, date('Y-m-d'), date('H:i:s'), 1);
            $addRespuesta = $consultaRespuesta->addRespuesta($_SESSION["user_ott"][0]["id_rol"]);

            if ($addRespuesta === true) {

              if ($_SESSION["user_ott"][0]["id_rol"] == 3) {

                $objetoConsulta->verificarConsulta(3);

                $usuario = new Usuario();
                $usuario->setEmail(descifrar(trim($_POST["email"]), KEY_ENCRYPT_ALL));
                $usuario->setEmailOpcional(descifrar(trim($_POST["email"]), KEY_ENCRYPT_ALL));
                // echo descifrar($_POST["email"], KEY_ENCRYPT_ALL);
                $userArray = $usuario->getUserByEmail($_SESSION["user_ott"][0]["id_rol"]);

                if (count($userArray) > 0) {

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
                    $mail->addAddress($userArray[0]["email"], $userArray[0]["nombre"]);

                    $mail->addCC(EMAIL_OTT, 'Ventanilla');
                    # Si existe un correo secundario se le envía la notificación también
                    if (!empty($userArray[0]["email_opcional"]))
                      $mail->addCC($userArray[0]["email_opcional"], $userArray[0]["nombre"]);

                    $mail->isHTML(true);

                    $mail->Subject = 'Requerimiento revisado - Ventanilla OTT';

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
                                      <td><h3>¡Hemos revisado tu Requerimiento - <strong>TICKET '.$idConsulta.'</strong>!</h3></td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <p>
                                          Te invitamos a revisar tu cuenta en la <a href="http://www.ventanilla.ott.cl/login.php">Ventanilla Virtual</a> para
                                          que te enteres de la verificación de tu requerimiento o consulta.<br>
                                          Gracias por comunicarte con nosotros, si tienes más consultas o dudas no dudes en contactarte con nosotros mediante la plataforma <a href="http://www.ventanilla.ott.cl/login.php">Ventanilla Virtual</a>.
                                          <br>
                                          Saludos.
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

                    $mail->send();
                  }catch (Exception $e) {

                  }

                }
              }
              else if ($_SESSION["user_ott"][0]["id_rol"] == 2) {

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
                  $mail->addAddress(EMAIL_OTT, 'Ventanilla');

                  $mail->addCC($_SESSION["user_ott"][0]["email"], ucfirst($_SESSION["user_ott"][0]["Nombre"]) . ' ' . ucfirst($_SESSION["user_ott"][0]["Apellido"]));
                  # Si existe un correo secundario se le envía la notificación también
                  if (!empty($_SESSION["user_ott"][0]["email_opcional"]))
                    $mail->addCC($_SESSION["user_ott"][0]["email_opcional"], ucfirst($_SESSION["user_ott"][0]["Nombre"]) . ' ' . ucfirst($_SESSION["user_ott"][0]["Apellido"]));

                  if (!empty($_SESSION["user_ott"][0]["email_empresa"]))
                    $mail->addCC($_SESSION["user_ott"][0]["email_empresa"], ucfirst($_SESSION["user_ott"][0]["Nombre"]) . ' ' . ucfirst($_SESSION["user_ott"][0]["Apellido"]));

                  $mail->isHTML(true);

                  $mail->Subject = 'Nueva consulta - Ventanilla OTT';

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
                                    <td><h3>¡Se ha realizado una consulta al requerimiento - <strong>TICKET '.$idConsulta.'</strong>!</h3></td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <p>
                                        Usuario '.ucfirst($_SESSION["user_ott"][0]["nombre"]).' '.ucfirst($_SESSION["user_ott"][0]["apellido"]).' ha realizado una consulta en su requerimiento, te invitamos a revisar tu cuenta en la <a href="http://www.ventanilla.ott.cl/login.php">Ventanilla Virtual</a> para
                                        que te enteres de las novedades sobre el requerimiento y las nuevas consultas.<br>
                                        Gracias por comunicarte con nosotros, si tienes más consultas o dudas no dudes en contactarte con nosotros mediante la plataforma <a href="http://www.ventanilla.ott.cl/login.php">Ventanilla Virtual</a>.
                                        <br>
                                        Saludos.
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

                  $mail->send();
                }catch(Exception $e) {

                }
              }

              echo '1|<div class="row">
                        <div class="'.($_SESSION["user_ott"][0]["id_usuario"] == descifrar($_POST["usuario"], KEY_ENCRYPT_ALL) ? 'col-xs-8' : 'col-xs-offset-4 col-xs-8').' mail-respuesta animated fadeInUp">
                          <div class="row">
                            <h5>
                              '.($_SESSION["user_ott"][0]["id_usuario"] == descifrar($_POST["usuario"], KEY_ENCRYPT_ALL) ? 'Yo' : ucfirst($_SESSION["user_ott"][0]["nombre"]).' '.ucfirst($_SESSION["user_ott"][0]["apellido"])).'
                              <br>
                              <small class="text-navy">'.ucfirst($_SESSION["user_ott"][0]["nombre_rol"]).'</small>
                            </h5>

                            <div class="col-xs-12">
                              '.trim($_POST["respuesta"]).'
                              <br>
                              <br>
                              <small class="pull-right text-navy">'.date('d/m/Y').' - '.date('H:i a').'</small>
                            </div>
                          </div>
                        </div>
                      </div>';

            }
            else {
              # No se añadio una respuesta
              echo 2;
            }
          }
          else {
            # No paso el filtro
            echo 3;
          }
        }
        else {
          # Camos vacíos
          echo 4;
        }
      }
      else {
        # Formulario alterado
        echo 5;
      }
      break;

    default:
      # code...
      break;
  }

  /**
   * Función que obtiene las respuestas de una consulta
   *
   * @param ID ROL del usuario
   * @param ID CONSULTA
   * @return Array con las respuestas de la consulta
   */
  function getRespuestasConsulta($id_rol, $id_consulta) {
    $consultaRespuesta = new ConsultaRespuesta(null, null, $id_consulta, null, null, null, 1);
    return $consultaRespuesta->getRespuestasConsulta($id_rol);
  }

  /**
   * Función que crea los botones que puede utilizar el usuario al momento de
   * una consulta
   *
   * @param ID ROL del usuario
   * @return HTML con los botones y sus respectivas acciones
   */
  function getButtonsRespuesta($id_rol, $id_consulta, $id_usuario_consulta) {
    $botones = '';
    $accionesExceptions = array(1, 2, 3, 4, 5);
    $acciones = getAccionesUser($id_rol);
    foreach ($acciones as $key => $value) {
      # WEB MASTER Y ADMINISTRADOR
      if ($id_rol == 1 || $id_rol == 3) {
        if (!in_array($value["id_accion"], $accionesExceptions)) {
          # Adjuntar archivo
          if ($value["id_accion"] == 6) {
            $botones .= "<label for='attachment_file' class='btn btn-info btn-outline pull-right'>
                          <form method='post' enctype='multipart/form-data' id='form_attachment_file'>
                            ".$value['icono']."
                            <input type='file' id='attachment_file' name='attachment_file' onchange='attachmentFile(&#39;".$id_consulta."&#39;, &#39;".$id_usuario_consulta."&#39;);' style='display:none;'> Adjuntar Archivo
                          </form>
                        </label>";
          }
          # Adjuntar email
          else if ($value["id_accion"] == 10) {
            $botones .= "<button type='button' class='btn btn-info btn-outline pull-right' data-toggle='modal' data-target='#attachment_email'>
                            ".$value['icono']."
                            Reenviar por correo
                        </button>
                        <div class='modal fade' id='attachment_email' role='dialog'>
                          <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                              <div class='modal-header'>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                <h4>Adjuntar correo electrónico.</h4>
                              </div>
                              <div class='modal-body'>
                                <div class='form-group'>
                                  <label for='attachment_email'>Correo electrónico</label>
                                  <input type='email' id='email_especialista' class='form-control' required placeholder='ejemplo@gmail.com'>
                                </div>
                                <div>
                                  <small class='text-danger'>Para enviar copias del correo debe agregar un ';' al final de cada correo electrónico. <br>Ejemplo: (ejemplo1@gmail.com;ejemplo2@outlook.com)</small>
                                </div>
                                <button type='button' onclick='attachmentEmail();' data-dismiss='modal' class='btn btn-primary pull-right'>
                                  <i class='fa fa-share' aria-hidden='true'></i> Enviar
                                </button>
                                <br>
                              </div>
                            </div>
                          </div>
                        </div>";
          }
        }
      }
      # USUARIO
      else  {

      }
    }
    return $botones;
  }



?>
