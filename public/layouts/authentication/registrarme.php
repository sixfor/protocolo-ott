<?php

  require_once 'controllers/controller.ficha.php';

  # Nombre de las comunas de la A-Z
  $comunas = getComunas();
  // asort($comunas);
  # Nombre de los meses
  $meses = getMeses();
  $mes = $meses[date('n')];
?>

<div class="row">
  <div class="col-xs-12 form-register">
    <div class="row">
      <div class="col-xs-12">
        <div class="col-md-8 col-xs-8">
          <h3>Registro de Usuario</h3>
          <small>Los campos marcados con un * son obligatorios.</small>
        </div>
        <div class="col-md-4 col-xs-2">
          <label class="pull-right" for="fecha"><?php echo date('d')."/".ucfirst($mes)."/".date('Y'); ?></label>
        </div>
      </div>
    </div>
    <hr>
    <div class="col-md-4 col-xs-12">
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
    </div>
    <div class="col-md-4 col-xs-12">
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
    <!-- <div class="col-md-4 col-xs-12">
      <div class="form-group">
        <label for="solicitud">Solicitud de Apoyo</label>
        <div class="alert alert-info" role="text">
          * Indicar problema u oportunidad que desea lograr. <br>
          * Describa sus principales produtos o servicios. <br>
          * Describa brevemente su proceso productivo.
        </div>
        <textarea name="solicitud" rows="8" cols="80" class="form-control" placeholder="Escribir solicitud de Apoyo"></textarea>
      </div>
    </div> -->
    <div class="col-xs-12">
      <button type="button" onclick="registrarme();"  id="enviar" class="btn btn-primary pull-right" style="margin: 5px 10px;">Enviar</button>
      <a href="login.php" class="pull-right">¡Espera! Regresar a la ventanilla principal.</a>
    </div>
  </div>
</div>
<script type="text/javascript">
  // $(function () {
  //
  //   $('#date_origen_actividad').datetimepicker({
  //
  //   });
  // });
</script>
