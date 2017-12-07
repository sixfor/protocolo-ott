<?php

  $usuarios = getUsers($_SESSION["user_ott"][0]["id_rol"]);
  $acciones = getAccionesOnModulo($_SESSION["user_ott"][0]["id_rol"], 3);
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
        <a href="#">Usuarios</a>
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
            <form onsubmit="buscarUsuario(); return false;">
              <div class="input-group">
                <input type="text" class="form-control input-sm" id="search" placeholder="Rut">
                <span class="input-group-btn">
                  <button type="button" onclick="buscarUsuario();" class="btn btn-sm btn-primary">
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
    <div class="col-lg-12 animated fadeInRight" style="margin-bottom: 100px;">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Usuarios</h5>
        </div>
        <div class="ibox-content">
          <table class="table table-condensed">
            <thead>
              <th>Nombre</th>
              <th>Email</th>
              <th>Rut</th>
              <th>Contacto</th>
              <th>Acciones</th>
            </thead>
            <tbody id="table_usuarios">
              <?php
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

                          if ($valueAccion["id_accion"] == 1) {
                            echo '<a href="?m='.$valueAccion["id_modulo"].'&a='.$valueAccion["id_accion"].'&u='.$value["id_usuario"].'">' . $valueAccion["icono"].'</a> &nbsp;&nbsp;';
                          }
                          else if ($valueAccion["id_accion"] == 12) {
                            echo '<a href="?m='.$valueAccion["id_modulo"].'&a='.$valueAccion["id_accion"].'&u='.$value["id_usuario"].'">' . $valueAccion["icono"].'</a> &nbsp;&nbsp;';
                          }
                          else if ($valueAccion["id_accion"] == 14) {
                            echo '<a onclick="deleteUser('.$value['id_usuario'].');"> '.$valueAccion["icono"].'</a> &nbsp;&nbsp;';
                          }
                        }
                      ?>
                    </td>
                  </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="public/js/ajax/usuario.js" charset="utf-8"></script>
