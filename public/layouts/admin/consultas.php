<?php
  $consultas = contentConsultasUser($_SESSION["user_ott"][0]["id_rol"]);
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
        <a href="#">Consultas</a>
      </li>
    </ol>
  </div>
  <div class="col-lg-2">
  </div>
</div>
<?php

  if ($_SESSION["user_ott"][0]["id_rol"] == 3) {
    ?>
    <div class="row">
      <div class="col-xs-12 animated fadeInRight cont-buscar-requerimiento">
        <div class="col-xs-12 col-md-4 col-md-offset-8">
          <div class="input-group">
            <input type="text" class="form-control input-sm" id="ticket" placeholder="Número de <?php echo $_SESSION["user_ott"][0]["id_rol"] == 3 ? 'Folio' : 'Ticket'; ?>">
            <span class="input-group-btn">
              <button type="button" onclick="buscarRequerimiento();" class="btn btn-sm btn-primary">
                Buscar
              </button>
            </span>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
?>
<!-- PAGE HEADER CONTENT -->
<div class="row">
  <div class="col-lg-12 animated fadeInRight">
    <?php echo $consultas; ?>
  </div>
</div>
