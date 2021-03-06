<?php

  # Nombre de las comunas de la A-Z
  $comunas = getComunas();
  // asort($comunas);
  # Nombre de los meses
  $meses = getMeses();
  $mes = $meses[date('n')];

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
        <a href="#">Nuevo Usuario</a>
      </li>
    </ol>
  </div>
  <div class="col-lg-2">
  </div>
</div>
<!-- PAGE HEADER CONTENT -->
<div class="row">
  <div class="col-lg-12 animated fadeInRight">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>Agregar Usuario</h5>
        <div class="ibox-tools">
          <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
          </a>
        </div>
      </div>
      <div class="ibox-content" style="margin-bottom: 100px;">
        <div class="row">
          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label for="password_contacto">* Contraseña:</label>
              <input type="text" id="password_contacto" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="nombre_contacto">* Nombre</label>
              <input type="text" id="nombre_contacto" class="form-control text-capitalize" placeholder="Juan" id="nombre_contacto" required>
            </div>
            <div class="form-group">
              <label for="apellido_contacto">* Apellido</label>
              <input type="text" id="apellido_contacto" class="form-control text-capitalize" placeholder="Perez" id="apellido_contacto" required>
            </div>
            <div class="form-group">
              <label for="email_contacto">* Email Contacto</label>
              <input type="email" id="email_contacto" class="form-control" placeholder="ejemplo@gmail.com" id="email_contacto" required>
            </div>
            <div class="form-group">
              <label for="email_opcional">Email Secundario (Opcional)</label>
              <input type="email" id="email_opcional" class="form-control" placeholder="ejemplo@gmail.com" id="email_opcional">
            </div>

            <!-- <div class="form-group">
              <label for="origen_actividad">Antigüedad de la Actividad</label> (En Meses)
              <input type="number" name="origen_actividad" class="form-control">
              <div class="input-group data" id="date_origen_actividad">
                <input type="text" name="origen_actividad" class="form-control" id="origen_actividad">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div> -->
          </div>
          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label for="razon_social">* Razón Social</label>
              <input type="text" class="form-control text-capitalize" id="razon_social" placeholder="Razón Social" id="razon_social" required>
            </div>
            <div class="form-group">
              <label for="rut">* Rut Empresa</label>
              <input type="text" class="form-control" id="rut" placeholder="19999999-K" id="rut" required>
            </div>
            <div class="form-group">
              <label for="act_productiva">Sector económico y Actividad productiva</label>
              <input type="text" id="act_productiva" class="form-control" placeholder="Sector económico y Actividad productiva" id="act_productiva" required>
            </div>
            <div class="form-group">
              <label for="direccion">* Dirección</label>
              <input type="text" id="direccion" class="form-control" placeholder="Dirección" id="direccion" required>
            </div>
            <div class="form-group">
              <label for="comunas">* Comuna</label>
              <select class="form-control" id="comuna">
                <?php
                  foreach ($comunas as $key => $value) {
                    echo '<option value="'.$value.'">'.ucfirst($value).'</option>';
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label for="telefono">Teléfono (63 2)</label>
              <input type="tel" id="telefono" class="form-control" placeholder="223344" id="telefono">
            </div>
            <div class="form-group">
              <label for="celular">* Celular (+569)</label>
              <input type="tel" id="celular" class="form-control" placeholder="99887766" id="celular" required>
            </div>
            <div class="form-group">
              <label for="email_empresa">Email Empresa</label>
              <input type="email" id="email_empresa" class="form-control" placeholder="empresa@gmail.com" id="email_empresa" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <button type="button" class="btn btn-primary pull-right" id="registrar" onclick="newUsuario();">
                <i class="fa fa-reply"></i>
                Registrar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="public/js/ajax/usuario.js" charset="utf-8"></script>
