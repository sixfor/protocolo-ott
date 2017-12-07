<!-- PAGE HEADING -->
<div class="row wrapper border-bottom white-bg page-heading m-b-sm">
  <div class="col-lg-10">
    <h2>Ventanilla</h2>
    <ol class="breadcrumb">
      <li>
        <a href="index.php">Inicio</a>
      </li>
      <li class="active">
        <a href="#">Nueva consulta</a>
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
        <h5>Ficha de Nueva Consulta. <small><?php echo '('. date('Y-m-d') . ')'; ?></small></h5>
        <div class="ibox-tools">
          <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
          </a>
        </div>
      </div>
      <div class="ibox-content" style="margin-bottom: 100px;">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="tiempo_solucion">Tiempo en que espera resolver el problema <span class="font-normal">(Meses)</span></label>
              <input type="number" class="form-control" id="tiempo_solucion" required>
            </div>
            <div class="form-group">
              <label for="problema_oportunidad">Indicar problema u oportunidad que desea abordar</label>
              <textarea id="problema_oportunidad" class="form-control" required></textarea>
            </div>
            <div class="form-group">
              <label for="met_prod_serv">Describa metodos, productos y/o servicios</label>
              <textarea id="met_prod_serv" class="form-control" required></textarea>
            </div>
            <div class="form-group">
              <label for="prod_obtener">Describa los principales productos que desea obtener</label>
              <textarea id="productos_obtener" class="form-control" required></textarea>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="proceso_productivo">Describa brevemente su proceso productivo</label>
              <textarea id="proceso_productivo" class="form-control" required></textarea>
            </div>
            <div class="form-group">
              <label for="solicitud">Solicitud de Apoyo</label>
              <textarea id="solicitud" class="form-control" rows="10" required></textarea>
            </div>
            <div class="form-group">
              <button type="button" class="btn btn-primary pull-right" id="enviar" onclick="newConsulta(<?php echo isset($_GET["u"]) && !empty($_GET["u"]) ? $_GET["u"] : $_SESSION['user_ott'][0]['id_usuario']; ?>);">
                <i class="fa fa-reply"></i>
                Consultar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="public/js/ajax/consulta.js" charset="utf-8"></script>
