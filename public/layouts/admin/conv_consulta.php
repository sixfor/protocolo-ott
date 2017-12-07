<?php
  $consulta = getConsultaUser(descifrar($_GET["i"], KEY_ENCRYPT_ALL), $_SESSION["user_ott"][0]["id_rol"]);
  $respuestas = getRespuestasConsulta($_SESSION["user_ott"][0]["id_rol"], $consulta[0]["id_consulta"]);
  $botonesRespuesta = getButtonsRespuesta($_SESSION["user_ott"][0]["id_rol"], encriptar($consulta[0]["id_consulta"], KEY_ENCRYPT_ALL), encriptar($consulta[0]["id_usuario"], KEY_ENCRYPT_ALL));
  $nombreTicket = getNombreTicket($_SESSION["user_ott"][0]["id_rol"]);
?>
<!-- PAGE HEADING -->
<div class="row wrapper border-bottom white-bg page-heading m-b-sm">
  <div class="col-lg-10">
    <h2>Ventanilla</h2>
    <ol class="breadcrumb">
      <li>
        <a href="index.php">Inicio</a>
      </li>
      <li>
        <a href="?m=1&a=2">Requerimientos</a>
      </li>
      <li class="active">
        <a href="#"><?php echo $nombreTicket . ' - ' .$consulta[0]["id_consulta"]; ?></a>
      </li>
    </ol>
  </div>
  <div class="col-lg-2">
    <input type="hidden" id='hidden_requerimiento' value="<?php echo $consulta[0]["solicitud"]; ?>">
    <input type="hidden" id="hidden_folio" value="<?php echo $consulta[0]["id_consulta"]; ?>">
  </div>
</div>
<!-- PAGE HEADER CONTENT -->
<div class="row">
  <div class="col-lg-12 animated fadeInRight" style="margin-bottom: 100px;">
    <div class="mail-box-header">
      <span class="pull-right font-normal">Fecha: <?php echo date_format(date_create($consulta[0]['hora']), 'H:i A') . ' - ' . date_format(date_create($consulta[0]['fecha']), 'd/m/Y'); ?></span>
      <!-- <div class="pull-right tooltip-demo">
        <a href="mail_compose.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Reply"><i class="fa fa-reply"></i> Reply</a>
        <a href="#" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Print email"><i class="fa fa-print"></i> </a>
        <a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i> </a>
      </div> -->
      <h2>
        Requerimiento - <strong><?php echo $nombreTicket . ' ' .$consulta[0]["id_consulta"]; ?></strong>
      </h2>
      <div class="mail-tools tooltip-demo m-t-md">
        <!-- <h3>
          <span class="font-normal">Subject: </span>Aldus PageMaker including versions of Lorem Ipsum.
        </h3> -->
        <span class="font-normal">De: </span> <strong><?php echo $consulta[0]['email']; ?></strong>  <span class="font-normal">(<?php echo ucfirst($consulta[0]["nombre"]).' '.ucfirst($consulta[0]["apellido"]); ?>)</span>
        <br>
        <span class="font-normal">Email secundario: </span><?php echo !empty($consulta[0]['email_opcional']) ? '<strong>'.$consulta[0]['email_opcional'].'</strong>' : 'No tiene'; ?>
        <br>
        <span class="font-normal">Rut: </span> <strong><?php echo $consulta[0]["rut"]; ?></strong>
        <br>
        <span class="font-normal">Contacto: </span>
        <strong>
          <?php
            echo !empty($consulta[0]["telefono"]) ? '<i class="fa fa-phone" aria-hidden="true"></i> '.$consulta[0]["telefono"].' ' : null;
            echo !empty($consulta[0]["celular"]) ? '<i class="fa fa-mobile" aria-hidden="true"></i> '.$consulta[0]["celular"] : null;
          ?>
        </strong>
        <br>
        <span class="font-normal">Tiempo de espera para resolver el problema (Meses): </span> <strong><?php echo $consulta[0]["antiguedad_actividad"]; ?></strong>
        <br>
        <span class="font-normal">Para: </span> <strong>Oficina de Transferencia Tecnológica</strong>
        <hr>
        <span class="font-normal">
          Documento Adjuntado:
          <br><br>
          <div id="documento-consulta">
            <?php
              echo !empty($consulta[0]["file"]) ? '<a href="public/download_file.php?c='.encriptar($consulta[0]["id_consulta"], KEY_ENCRYPT_ALL).'&u='.encriptar($_SESSION["user_ott"][0]["id_usuario"], KEY_ENCRYPT_ALL)."&f=".encriptar($consulta[0]["file"], KEY_ENCRYPT_ALL).'" target="_blank"><button type="button" class="btn btn-outline btn-info dim">'.$consulta[0]["file"].' <i class="fa fa-download" aria-hidden="true"></i></button> </a>' : 'No tiene un documento asociado a su solicitud.';
            ?>
          </div>
        </span>
      </div>
    </div>
    <div class="mail-box">
      <div class="mail-body">
        <div class="row">
          <div class="col-xs-12">
            <div class="cont-consulta">
              <h3>Problema u Oportunidad</h3>
              <p><?php echo $consulta[0]["problema_oportunidad"]; ?></p>
              <h3>Metodos, Productos y/o Servicios</h3>
              <p><?php echo $consulta[0]["met_prod_serv"]; ?></p>
              <h3>Principales productos a obtener</h3>
              <p><?php echo $consulta[0]["productos_obtener"]; ?></p>
              <h3>Proceso productivo</h3>
              <p><?php echo $consulta[0]["proceso_productivo"]; ?></p>
              <h3>Solicitud de Apoyo</h3>
              <p><?php echo $consulta[0]["solicitud"]; ?></p>

            </div>
          </div>
        </div>
        <div class="cont-respuestas">
          <?php
            # Si la consulta tiene respuestas se mostrarán
            if (count($respuestas) > 0) {
              foreach ($respuestas as $keyRespuesta => $valueRespuesta) {

                $userRespuesta = $valueRespuesta["id_usuario"] == $_SESSION["user_ott"][0]["id_usuario"] ? true : false;

                ?>
                <div class="row">
                  <div class="col-xs-8 <?php echo $userRespuesta ? null : 'col-xs-offset-4'; ?> mail-respuesta">
                    <div class="row">
                      <h5>
                        <?php echo $userRespuesta ? 'Yo' : ucfirst($valueRespuesta["nombre"]) .' '. ucfirst($valueRespuesta["apellido"]); ?>
                        <br>
                        <small class="text-navy"><?php echo ucfirst($valueRespuesta["nombre_rol"]); ?></small>
                      </h5>
                      <div class="col-xs-12">
                        <?php
                          echo $valueRespuesta["respuesta"];
                        ?>
                        <br><br>
                        <small class="pull-right text-navy"><?php echo date_format(date_create($valueRespuesta["fecha"]), 'd/m/Y').' - '.date_format(date_create($valueRespuesta["hora"]), 'H:i a'); ?></small>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            }
          ?>
        </div>
        <?php
          $consultaRespuesta = new ConsultaRespuesta(null, null, $consulta[0]["id_consulta"], null, null, null, 1);
          # Se cuentan las respuestas del requerimiento
          $cantidadRespuestas = $consultaRespuesta->countRespuestasConsulta($_SESSION["user_ott"][0]["id_rol"]);
          $sw = true;

          if ($cantidadRespuestas[0]["cantidad_respuestas"] == 0) {

            $fechaExpiracion = strtotime('+72 hour', strtotime($consulta[0]["fecha"]));
            $fechaExpiracion = date('Y-m-d', $fechaExpiracion);
            $fechaExpiracionCompleta = $fechaExpiracion . ' ' . $consulta[0]["hora"];
            // echo $fechaExpiracionCompleta . ' ' . date('Y-m-d H:i:s');
            if (date('Y-m-d H:i:s') > $fechaExpiracionCompleta) {
              $sw = false;
            }
          }

          if ($consulta[0]["verificador"] != 2 && $sw) {
        ?>
        <div class="row">
          <div class="col-xs-12 mail-footer">
            <div class="form-group">
              <label for="mensaje">Nueva consulta</label>
              <textarea id="mensaje" rows="5" class="form-control" placeholder="Haga click para responder aquí."></textarea>
            </div>
            <br>
            <?php
              if ($_SESSION["user_ott"][0]["id_rol"] == 3) {
                echo '<div class="row">
                        <div class="col-xs-12">
                          <p>Verificar requerimiento.</p>
                          <div class="radio radio-inline">
                            <input type="radio" id="enRevision" value="0" name="verificar" '.($consulta[0]["verificador"] == 0 ? 'checked' : null).'>
                            <label for="enRevision">En revisión</label>
                          </div>
                          <div class="radio radio-success radio-inline">
                            <input type="radio" id="aceptar" value="1" name="verificar" '.($consulta[0]["verificador"] == 1 ? 'checked' : null).'>
                            <label for="aceptar">Aceptar</label>
                          </div>
                          <div class="radio radio-danger radio-inline">
                            <input type="radio" id="rechazar" value="2" name="verificar">
                            <label for="rechazar">Rechazar</label>
                          </div>
                        </div>
                      </div>';
              }
            /**
             * NOTA: responderConsulta();
             * Esta funcion tiene 3 parametros obligatorios
             * @param (1) ID CONSULTA
             * @param (2) ID USUARIO que responde
             * @param (3) ID USUARIO que creo la consulta
             * @param (4) EMAIL al que se le enviara el correo de que tiene una respuesta
             */
             ?>
            <hr>
            <div class="row">
              <div class="col-xs-12">
                <div class="buttons-respuesta">
                  <button type="button" id="responder" class="btn btn-primary pull-right" onclick="responderConsulta('<?php echo encriptar($consulta[0]["id_consulta"], KEY_ENCRYPT_ALL); ?>', '<?php echo encriptar($_SESSION["user_ott"][0]["id_usuario"], KEY_ENCRYPT_ALL); ?>', '<?php echo encriptar($consulta[0]["email"], KEY_ENCRYPT_ALL); ?>');">
                    <i class="fa fa-reply"></i>
                    Responder
                  </button>
                  <?php
                  if (!empty($botonesRespuesta))
                  echo $botonesRespuesta;
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
        }
        ?>
      </div>

      <!-- <div class="mail-attachment">
        <p>
          <span><i class="fa fa-paperclip"></i> 2 attachments - </span>
          <a href="#">Download all</a>
          |
          <a href="#">View all images</a>
        </p>

        <div class="attachment">
          <div class="clearfix"></div>
        </div>
      </div> -->
      <!-- <div class="mail-body text-right tooltip-demo">
        <a class="btn btn-sm btn-white" href="mail_compose.html"><i class="fa fa-reply"></i> Reply</a>
        <a class="btn btn-sm btn-white" href="mail_compose.html"><i class="fa fa-arrow-right"></i> Forward</a>
        <button title="" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Print" class="btn btn-sm btn-white"><i class="fa fa-print"></i> Print</button>
        <button title="" data-placement="top" data-toggle="tooltip" data-original-title="Trash" class="btn btn-sm btn-white"><i class="fa fa-trash-o"></i> Remove</button>
      </div> -->
      <div class="clearfix"></div>
    </div>
  </div>
</div>
<script src="public/js/ajax/consulta.js" charset="utf-8"></script>
