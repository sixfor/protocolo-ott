<?php

  $consultas = array();

  # Usuario normal
  if ($_SESSION["user_ott"][0]["id_rol"] == 2) {
    # Se pasa como parametro el ID USUARIO para que obtener solamente sus consultas
    $consulta = new Consulta(null, null, null, $_SESSION["user_ott"][0]["id_usuario"], null, null, null, 1, null);
    $consultas = $consulta->getConsultas($_SESSION["user_ott"][0]["id_rol"]);
  }
  # Administrador
  else if ($_SESSION["user_ott"][0]["id_rol"] == 3) {
    # No se pasa por parametro el ID USUARIO ya que se pretende obtener todas las consultas habilitadas
    $consulta = new Consulta(null, null, null, null, null, null, null, 1, null);
    $consultas = $consulta->getConsultas($_SESSION["user_ott"][0]["id_rol"]);
  }

?>

<!-- PAGE HEADING -->
<div class="row wrapper border-bottom white-bg page-heading m-b-sm">
  <div class="col-lg-10">
    <h2>Ventanilla</h2>
    <ol class="breadcrumb">
      <li>
        <a href="index.php">Inicio</a>
      </li>
      <li class="active">
        <a href="?m=1&a=2">Requerimientos</a>
      </li>
    </ol>
  </div>
  <div class="col-lg-2">
  </div>
</div>
<div class="wraper wraper-content animated fadeInRight">
  <?php

    if ($_SESSION["user_ott"][0]["id_rol"] == 3) {
      ?>
      <div class="row">
        <div class="col-xs-12 animated fadeInRight cont-buscar-requerimiento">
          <div class="col-xs-12 col-md-4 col-md-offset-8">
            <form onsubmit="buscarRequerimiento(); return false;">
              <div class="input-group">
                <input type="text" class="form-control input-sm" id="search" placeholder="Buscar por Folio o RUT">
                <span class="input-group-btn">
                  <button type="button" onclick="buscarRequerimiento();" class="btn btn-sm btn-primary">
                    Buscar
                  </button>
                </span>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php
    }
  ?>
  <!-- PAGE HEADER CONTENT -->
  <div class="row">
    <div class="col-lg-12" style="margin-bottom: 100px;">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5><?php echo $_SESSION["user_ott"][0]["id_rol"] == 2 ? 'Mis Requerimientos' : 'Requerimientos'; ?></h5>
        </div>
        <div class="ibox-content">
          <div class="feed-activity-list" id="feed-requerimientos">
            <?php
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
                if ($_SESSION["user_ott"][0]["id_rol"] == 2) {
                  echo 'No existen requerimientos. <a href="?m=1&a=1">¿Desea crear un requerimiento?</a>';
                }
                else if ($_SESSION["user_ott"][0]["id_rol"] == 3) {
                  echo 'No existen requerimientos para mostrar.';
                }
              }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="public/js/ajax/consulta.js" charset="utf-8"></script>
