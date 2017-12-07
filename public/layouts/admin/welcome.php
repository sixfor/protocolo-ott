<?php

  $modulos = getModulosUser($_SESSION["user_ott"][0]["id_rol"]);

?>
<!-- PAGE HEADING -->
<div class="row wrapper border-bottom white-bg page-heading m-b-sm">
  <div class="col-lg-10">
    <h2>Ventanilla</h2>
    <ol class="breadcrumb">
      <li>
        <a href="index.html">Inicio</a>
      </li>
    </ol>
  </div>
  <div class="col-lg-2">
  </div>
</div>
<!-- PAGE HEADER CONTENT -->

<!-- <div class="row border-bottom white-bg dashboard-header">

</div> -->
<div class="wraper wraper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-content text-center p-md">
          <h2>
            <span class="text-navy">¡Bienvenido! <?php echo $_SESSION["user_ott"][0]["nombre"]." ".$_SESSION["user_ott"][0]["apellido"]; ?> a la Ventanilla Virtual</span>
          </h2>
          <p>
          Aquí es donde usted podrá crear sus consultas y requerimientos para enviarlos hacia la <br>
          oficina de transferencia tecnológica  donde serán evaluados y contestados en el menor tiempo posible.
          </p>
          </div>
        </div>
      </div>
    </div>
    <div class="row" style="padding-bottom: 100px;">
      <?php
      foreach ($modulos as $key => $value) {
        ?>
        <div class="col-xs-12">
          <div class="ibox float-e-margins">
            <div class="ibox-content text-center p-md">
              <h4 class="text-capitalize"><?php echo $value['nombre']; ?></h4>
              <small>Accesos directos</small>
              <p>Usted tiene disponible las siguientes opciones:</p>
              <?php

                $acciones = getAccionesOnModulo($_SESSION["user_ott"][0]["id_rol"], $value['id_modulo']);
                $accionesExceptions = array(3, 4, 5, 6, 7, 10, 12, 14);
                foreach ($acciones as $keyAccion => $valueAccion) {
                  if (!in_array($valueAccion['id_accion'], $accionesExceptions))
                  echo '<span class="simple_tag text-capitalize"><a href="?m='.$value['id_modulo'].'&a='.$valueAccion['id_accion'].'">'.$valueAccion['nombre'].'</a></span>';
                }
              ?>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
  </div>
