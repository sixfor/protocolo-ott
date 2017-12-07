<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  /**
  * Manejador AJAX
  * Según el valor de $_GET["action"] se ejecuta la respectiva acción
  */
  switch (isset($_GET["action"]) ? $_GET["action"] : null) {

    case 'new_consulta':

    if (isset($_POST["solicitud"]) && isset($_POST["usuario"]) && isset($_POST["problemaOportunidad"]) &&
    isset($_POST["metProdServ"]) && isset($_POST["productosObtener"]) && isset($_POST["procesoProductivo"])) {

      if (!empty($_POST["solicitud"]) && !empty($_POST["usuario"]) && !empty($_POST["problemaOportunidad"]) &&
      !empty($_POST["metProdServ"]) && !empty($_POST["productosObtener"]) && !empty($_POST["procesoProductivo"])) {

        require_once 'controller.filtro.php';

        $tiempoSolucion = limpiar(trim($_POST["tiempoSolucion"]));
        $solicitud = limpiar(trim($_POST["solicitud"]));
        $usuario = limpiar(trim($_POST["usuario"]));
        $procesoProductivo = limpiar($_POST["procesoProductivo"]);
        $metProdServ = limpiar($_POST["metProdServ"]);
        $productosObtener = limpiar($_POST["productosObtener"]);
        $procesoProductivo = limpiar($_POST["procesoProductivo"]);

        if ($solicitud == strtolower(trim($_POST["solicitud"])) && $usuario == strtolower(trim($_POST["usuario"])) && $tiempoSolucion == strtolower(trim($_POST["tiempoSolucion"])) &&
        $procesoProductivo == strtolower($_POST["procesoProductivo"]) && $metProdServ == strtolower($_POST["metProdServ"]) && $productosObtener == strtolower($_POST["productosObtener"]) && $procesoProductivo == strtolower($_POST["procesoProductivo"])) {

          require_once '../ack/ack.php';
          require_once '../models/class.conexion.php';
          require_once '../models/class.consulta.php';
          require_once '../models/class.user.php';

          require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
          require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
          require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';


          date_default_timezone_set('America/Santiago');
          session_start();

          $consulta = new Consulta(null, date('Y-m-d'), date('H:i:s'), trim($_POST["usuario"]), 0, trim($_POST["solicitud"]), null, null, null, trim($_POST["tiempoSolucion"]), trim($_POST["problemaOportunidad"]), trim($_POST["metProdServ"]), trim($_POST["productosObtener"]), trim($_POST["procesoProductivo"]));
          $proximoId = $consulta->getProximoId($_SESSION["user_ott"][0]["id_rol"]);
          $newConsulta = $consulta->newConsulta($_SESSION["user_ott"][0]["id_rol"]);

          $objUser = new Usuario();
          $objUser->setId(trim($_POST["usuario"]));
          $userArray = $objUser->getUser($_SESSION["user_ott"][0]["id_rol"]);

          if ($newConsulta === true) {

            $mail = new PHPMailer(true);
            try {
              # Envía el mail para que el usuario cree su contraseña

              // $mail->SMTPDebug = 3;
              $mail->isSMTP();
              $mail->Host = HOST_EMAIL;
              $mail->SMTPAuth = true;
              $mail->Username = SERVER_EMAIL;
              $mail->Password = SERVER_EMAIL_PASSWORD;
              $mail->SMTPSecure = SMTP_SECURE;
              $mail->Port = SMTP_PORT;
              // $mail->CharSet = 'UTF-8';

              $mail->setFrom(SERVER_EMAIL, 'Ventanilla OTT');
              $mail->addAddress(EMAIL_OTT, 'Proyecto Valdivia');
              $mail->addCC($userArray[0]["email"], ucfirst($userArray[0]["nombre"]) . ' ' . ucfirst($userArray[0]["apellido"]));

              if (!empty($userArray[0]["email_empresa"]))
                $mail->addCC($userArray[0]["email_empresa"], ucfirst($userArray[0]["nombre"]) . ' ' . ucfirst($userArray[0]["apellido"]));

              if (!empty($userArray[0]["email_opcional"]))
                $mail->addCC($userArray[0]["email_opcional"], ucfirst($userArray[0]["nombre"]) . ' ' . ucfirst($userArray[0]["apellido"]));

              $mail->isHTML(true);
              $mail->Subject = 'Nuevo requerimiento - Ventanilla OTT';

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
                              <th><h1>Nuevo Requerimiento en la Ventanilla Virtual</th>
                            </thead>
                            <tbody>
                              <tr>
                                <td><h3>Usuario '.ucfirst($userArray[0]["nombre"]). ' '.ucfirst($userArray[0]["apellido"]).' ha realizado un nuevo requerimiento.</h3></td>
                              </tr>
                              <tr>
                                <td>
                                  <p>
                                    '.((count($proximoId) == 1) ? 'Número Requerimiento: Ticket <strong>'.$proximoId[0][0].'</strong><br>' : null).'
                                    Usuario: '.ucfirst($userArray[0]["nombre"]). ' '.ucfirst($userArray[0]["apellido"]).' <br>
                                    Rut: '.$userArray[0]["rut"].' <br>
                                    Problema a Abordar: <br> '.ucfirst($_POST["problemaOportunidad"]).' <br><br>
                                    Ingresar a la ventanilla para ver más detalles de este nuevo requerimiento.
                                    <br>
                                    http://www.ventanilla.ott.cl/
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

            # Consulta registrada
            echo 1;
          }
          else {
            # Consulta no registrada
            echo 2;
          }
        }
        else {
          # No paso el filtro
          echo 3;
        }
      }
      else {
        # Formulario vacío
        echo 4;
      }
    }
    else {
      # Formulario alterado
      echo 5;
    }

    break;

    case 'attachment_file':

    if (isset($_FILES["attachment_file"]) && !empty($_FILES["attachment_file"]["name"]) &&
    isset($_POST["consulta"]) && !empty($_POST["consulta"]) &&
    isset($_POST["usuarioConsulta"]) && !empty($_POST["usuarioConsulta"])) {

      require_once '../ack/ack.php';
      require_once '../models/class.conexion.php';
      require_once '../models/class.consulta.php';
      require_once 'controller.filtro.php';
      require_once 'controller.upload_file.php';

      $name = limpiar($_FILES["attachment_file"]["name"]);
      $type = limpiar($_FILES["attachment_file"]["type"]);
      $consulta = limpiar($_POST["consulta"]);
      $usuarioConsulta = limpiar($_POST["usuarioConsulta"]);

      if ($name == strtolower($_FILES["attachment_file"]["name"]) && $type == strtolower($_FILES["attachment_file"]["type"]) &&
      $consulta == strtolower($_POST["consulta"]) && $usuarioConsulta == strtolower($_POST["usuarioConsulta"])) {

        session_start();

        $consultaDescifrada = descifrar(trim(urldecode($_POST["consulta"])), KEY_ENCRYPT_ALL);
        $usuarioConsultaDescifrado = descifrar(trim(urldecode($_POST["usuarioConsulta"])), KEY_ENCRYPT_ALL);

        $consulta = new Consulta($consultaDescifrada, null, null, null, null, null, null, 1, null);
        $consultaExistente = $consulta->getConsultaUser($_SESSION["user_ott"][0]["id_rol"]);

        if (count($consultaExistente) == 1) {
          /**
          * Con este if nos aseguramos de que el administrador este subiendo el documento
          * a la consulta correcta y evitamos que borre un documento de otra consulta que pertenezca a OTRO
          * usuario con un posible cambio de ID CONSULTA
          */
          if ($consultaExistente[0]["id_usuario"] == $usuarioConsultaDescifrado) {

            date_default_timezone_set('America/Santiago');

            $extension = get_extension($_FILES["attachment_file"]["type"]);
            $fileName = date('Y-m-d-H-i-s') . $extension;
            $fileType = trim($_FILES["attachment_file"]["type"]);
            $fileNameTmp = trim($_FILES["attachment_file"]["tmp_name"]);
            $fileSize = trim($_FILES["attachment_file"]["size"]);

            $destino = '../public/documents/';
            $prefijo = $consultaExistente[0]["id_consulta"] . '_';

            /**
            * Si existe una consulta y el campo FILE de la tabla consulta no es vacío
            * quiere decir que ya existía un documento asociado a la consulta, se procede
            * a eliminar para subir el nuevo documento
            */
            if (!empty($consultaExistente[0]["file"])) {
              unlink($destino.$consultaExistente[0]["file"]);
            }
            $uploadFile = subir_archivo($fileName, $fileNameTmp, $fileType, $fileSize, $destino, $prefijo);

            if (!empty($uploadFile)) {

              $consulta = new Consulta($consultaDescifrada, date('Y-m-d'), date('H:i:s'), null, null, null, 1, null, $uploadFile, null);
              # Actualiza el nombre del archivo en la tabla
              $updateFile = $consulta->updateFile($_SESSION["user_ott"][0]["id_rol"]);

              # Marca como leído
              $consulta->leido($_SESSION["user_ott"][0]["id_rol"]);

              if ($updateFile === true) {
                $consulta = new Consulta($consultaDescifrada, null, null, null, null, null, null, 1 ,null, null);
                $consultaArray = $consulta->getConsultaUser($_SESSION["user_ott"][0]["id_rol"]);
                $showFile = '<a href="public/download_file.php?c='.encriptar($consultaDescifrada, KEY_ENCRYPT_ALL).'&u='.encriptar($_SESSION["user_ott"][0]["id_usuario"], KEY_ENCRYPT_ALL)."&f=".encriptar($consultaArray[0]["file"], KEY_ENCRYPT_ALL).'" target="_blank">
                              <button type="button" class="btn btn-outline btn-info dim">'.$consultaArray[0]["file"].' <i class="fa fa-download" aria-hidden="true"></i></button>
                             </a>';
                echo '1|'.$showFile;
              }
              # No se modifico
              else {
                echo 2;
              }
            }
            # No se ha subido el archivo
            else {
              echo 3;
            }
          }
          # No es el mismo usuario de la consulta
          else {
            echo 4;
          }
        }
        # Usuario de la consulta no existe, posible cambio de ID USUARIO
        else {
          echo 5;
        }
      }
      # Posible inyeccion SQL
      else {
        echo 6;
      }
    }
    # Formulario alterado o vacío
    else {
      echo 7;
    }
    break;

    default:
    break;

    case 'attachment_email':

      if (isset($_POST["email"]) && isset($_POST["folio"]) && isset($_POST["requerimiento"])) {
        if (!empty($_POST["email"]) && !empty($_POST["folio"]) && !empty($_POST["requerimiento"])) {

          require_once '../ack/ack.php';
          require_once '../vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

          $emailArray = explode(';', strtolower(trim($_POST["email"])));
          $folio = $_POST["folio"];
          $requerimiento = trim($_POST["requerimiento"]);

          $mail = new PHPMailer;

          # $mail->SMTPDebug = 3;
          $mail->isSMTP();
          $mail->Host = HOST_EMAIL;
          $mail->SMTPAuth = true;
          $mail->Username = SERVER_EMAIL;
          $mail->Password = SERVER_EMAIL_PASSWORD;
          $mail->SMTPSecure = SMTP_SECURE;
          $mail->Port = SMTP_PORT;

          $mail->setFrom(SERVER_EMAIL, 'Ventanilla OTT');

          $email = $emailArray[0];
          $mail->addAddress($email);

          for ($i = 0; $i < count($emailArray); $i++) {
            if ($i > 0 && !empty($emailArray[$i]))
              $mail->addCC($emailArray[$i]);
          }

          $mail->isHTML(true);

          $mail->Subject = 'Ayuda en Requerimiento - Ventanilla OTT';

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
                            <td><h3>Requerimiento - <strong>TICKET '.$folio.'</strong>!</h3></td>
                          </tr>
                          <tr>
                            <td>
                              <p>'.$requerimiento.'</p>
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
          if ($mail->send()) {
            echo 1;
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

      case 'buscar_requerimiento':

        if (isset($_POST["search"])) {

          require_once '../ack/ack.php';
          require_once 'controller.filtro.php';
          require_once '../models/class.conexion.php';
          require_once '../models/class.consulta.php';
          require_once '../models/class.consulta.respuesta.php';
          require_once '../models/class.accion.php';
          require_once 'controller.accion.php';

          $search = limpiar($_POST["search"]);

          if ($search == strtolower($_POST["search"])) {
            session_start();

            $search = trim(str_replace(".", "", $_POST["search"]));

            $consulta = new Consulta(null, null, null, null, null, null, null, 1, null, null);
            if (empty(trim($_POST["search"]))) {
              $consultas = $consulta->getConsultas($_SESSION["user_ott"][0]["id_rol"]);
            }
            else {
              $consultas = $consulta->getConsultaCustom($_SESSION["user_ott"][0]["id_rol"], $search);
            }

            if (count($consultas) > 0) {

              foreach ($consultas as $keyConsulta => $valueConsulta) {
                ?>

                <div class="feed-element">
                  <div class="row">
                    <div class="col-xs-12 col-md-8">
                      <div class="col-xs-12 col-md-1">
                        <a href="#">
                          <img <?php echo 'src="public/img/'.$valueConsulta["icono_perfil"].'"'; ?> alt="icono_perfil" class="img-circle">
                        </a>
                      </div>
                      <div class="media-body">
                        <?php
                          if ($_SESSION["user_ott"][0]["id_rol"] == 2) {
                            echo '<strong class="text-capitalize">Yo</strong> envié un requerimiento a la <strong>Oficina de Transferencia Tecnológica.</strong>';
                          }
                          else if ($_SESSION["user_ott"][0]["id_rol"] == 3) {
                            echo '<strong class="text-capitalize">Oficina de Transferencia Tecnológica</strong> recibió un requerimiento de <strong class="text-capitalize">'.$valueConsulta['nombre'].' '.$valueConsulta['apellido'].'</strong>';
                          }
                        ?>
                        <br>
                        <small class="text-muted">
                          Fecha origen:
                          <?php
                            echo date_format(date_create($valueConsulta["fecha"]), 'd/m/Y').' - '.strtolower(date_format(date_create($valueConsulta["hora"]), 'H:i A'));

                            $consultaRespuesta = new ConsultaRespuesta(null, null, $valueConsulta["id_consulta"], null, null, null, 1);
                            # Se cuentan las respuestas del requerimiento
                            $cantidadRespuestas = $consultaRespuesta->countRespuestasConsulta($_SESSION["user_ott"][0]["id_rol"]);

                            for ($i = 0; $i < count($cantidadRespuestas); $i++) {
                              # Si el requerimiento no ha sido respondido se muestran los días que quedan para hacerlo
                              if ($cantidadRespuestas[0]["cantidad_respuestas"] == 0) {

                                $fechaCompleta = $valueConsulta["fecha"] . ' ' . $valueConsulta["hora"];

                                $fechaExpiracion = strtotime('+72 hour', strtotime($valueConsulta["fecha"]));
                                $fechaExpiracion = date('d/m/Y', $fechaExpiracion);

                                $fechaExpiracionCompleta = $fechaExpiracion .' '. strtolower(date_format(date_create($valueConsulta["hora"]), 'H:i A'));

                                # Entra si esta Expirado
                                if (date('d/m/Y H:i:s') > $fechaExpiracionCompleta) {
                                  echo "<br><label class='text-danger'>Expirado</label>";
                                }
                                # Entra si aun no ha expirado
                                else if (date('d/m/Y H:i:s') <= $fechaExpiracionCompleta) {
                                  $newFechaExpiracion = strtotime('+72 hour', strtotime($valueConsulta["fecha"]));
                                  $newFechaExpiracion = date('Y-m-d', $newFechaExpiracion);
                                  $intervalo = date_diff(date_create(date('Y-m-d')), date_create($newFechaExpiracion));

                                  if ($intervalo->format('%d') == 2) {
                                    echo '<br><label class="text-warning">' . $intervalo->format('%d días para responder.'). '</label>';
                                  }
                                  else {
                                    echo '<br><label class="text-success">' . $intervalo->format('%d días para responder.'). '</label>';
                                  }
                                }
                              }
                            }

                          ?>
                        </small>
                      </div>
                    </div>
                    <div class="col-xs-12 col-md-2">

                      <?php
                        # Usuario Normal
                        if ($_SESSION["user_ott"][0]["id_rol"] == 2) {
                          echo '<strong class='.($valueConsulta["leido"] == 1 && $valueConsulta["verificador"] != 2 ? 'text-navy' : null).'>
                                  <i class="fa fa-ticket" aria-hidden="true"></i> Ticket '.$valueConsulta['id_consulta'].($valueConsulta["verificador"] == 2 ? '<br><small class="text-danger" data-toggle="tooltip" data-placement="top" title="Rechazado" data-original-title="Rechazado">Rechazado</small>' : ($valueConsulta["verificador"] == 1 ? '<br><small class="text-success" data-toggle="tooltip" data-placement="top" title="Aceptado" data-original-title="Aceptado">Aceptado</small>' : ($valueConsulta["verificador"] == 0 ? '<br><small class="text-primary" data-toggle="tooltip" data-placement="top" title="En Revisión" data-original-title="En Revisión">En Revisión</small>' : null))).'
                                </strong>';
                        }
                        # Administrador
                        else if ($_SESSION["user_ott"][0]["id_rol"] == 3) {
                          echo '<strong class='.($valueConsulta["leido"] == 0 && $valueConsulta["verificador"] != 2 ? 'text-navy' : null).'>
                                  <i class="fa fa-ticket" aria-hidden="true"></i> Folio '.$valueConsulta['id_consulta'].($valueConsulta["verificador"] == 2 ? '<br><small class="text-danger" data-toggle="tooltip" data-placement="top" title="Rechazado" data-original-title="Rechazado">Rechazado</small>' : ($valueConsulta["verificador"] == 1 ? '<br><small class="text-success" data-toggle="tooltip" data-placement="top" title="Aceptado" data-original-title="Aceptado">Aceptado</small>' : ($valueConsulta["verificador"] == 0 ? '<br><small class="text-primary" data-toggle="tooltip" data-placement="top" title="En Revisión" data-original-title="En Revisión">En Revisión</small>' : null))).'
                                </strong>';
                        }
                      ?>

                    </div>
                    <div class="col-xs-12 col-md-2">
                      <?php
                        # 1 hace referencia al modulo CONSULTA
                        $acciones = getAccionesOnModulo($_SESSION["user_ott"][0]["id_rol"], 1);
                        # las acciones con los siguientes ID no se mostraran
                        $exceptionAccion = array(1, 2);
                        foreach ($acciones as $keyAccion => $valueAccion) {
                          if (!in_array($valueAccion["id_accion"], $exceptionAccion)) {
                            # Leer consulta
                            if ($valueAccion["id_accion"] == 3) {
                              echo '<a class="btn btn-sm '.($valueConsulta["leido"] == 1 && $valueConsulta["verificador"] != 2 ? 'btn-primary' : 'btn-white').'" href="?m=1&a=3&i='.encriptar($valueConsulta["id_consulta"], KEY_ENCRYPT_ALL).'" >'.$valueAccion["icono"].' '.ucfirst($valueAccion["nombre"]).'</a>';
                            }
                            # Responder consulta
                            if ($valueAccion["id_accion"] == 5) {
                              echo '<a class="btn btn-sm '.($valueConsulta["leido"] == 0 && $valueConsulta["verificador"] != 2 ? 'btn-primary' : 'btn-white').'" href="?m=1&a=3&i='.encriptar($valueConsulta["id_consulta"], KEY_ENCRYPT_ALL).'">'.$valueAccion["icono"].' '. ucfirst($valueAccion["nombre"]).'</a>';
                            }
                          }
                        }
                      ?>
                    </div>
                  </div>
                </div>

                <?php
              }
            }
            else {
              echo 'No se encontró el requerimiento.';
            }
          }
          else {
            echo 'No se encontró el requerimiento.';
          }
        }
        else {
          echo 'No se encontró el requerimiento.';
        }
        break;
  }

  # Funcion para definir el nombre que tendra el ticket segun el ID ROL
  function getNombreTicket($id_rol) {
    $nameTicket = array(
      1 => 'Folio',
      2 => 'Ticket',
      3 => 'Folio'
    );
    return $nameTicket[$id_rol];
  }

  /**
  * Funcion que obtiene una consulta segun su ID
  */
  function getConsultaUser($id_consulta, $id_rol) {
    $consulta = new Consulta($id_consulta, null, null, null, null, null, null, 1, null);
    return $consulta->getConsultaUser($id_rol);
  }

  /**
  * Funcion que obtiene la cantidad de consultas no leidas por el usuario
  */
  function consultasNoLeidasUser() {
    $consulta = new Consulta(null, null, null, $_SESSION["user_ott"][0]["id_usuario"], null, null, 0, 1, null);
    return $consulta->countConsultasUser($_SESSION["user_ott"][0]["id_rol"]);
  }

?>
