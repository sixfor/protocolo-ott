<?php

  error_reporting(E_ALL);

  require_once 'ack/ack.php';
  require_once 'models/class.conexion.php';
  require_once 'models/class.user.php';
  require_once 'models/class.modulo.php';
  require_once 'models/class.accion.php';
  require_once 'models/class.consulta.php';
  require_once 'models/class.consulta.respuesta.php';
  // require_once 'models/class.user.php';
  require_once 'controllers/controller.filtro.php';
  require_once 'controllers/controller.functions.php';
  require_once 'controllers/controller.modulo.php';
  require_once 'controllers/controller.accion.php';
  require_once 'controllers/controller.consulta.php';
  require_once 'controllers/controller.consulta.respuesta.php';
  require_once 'controllers/controller.usuario.php';

  session_start();

  // var_dump($_SESSION["user_ott"]);

  if (!isset($_SESSION["user_ott"]))
    header('location: login.php');

  if (isset($_GET["login"]) == "logout") {
    session_destroy();
    header('location: index.php');
  }

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="public/plugins/bootstrap-3.3.7-dist/css/bootstrap.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="public/font-awesome/css/font-awesome.css">
    <!-- Bootstrap Datetimepicker -->
    <link rel="stylesheet" href="public/plugins/bootstrap-3.3.7-dist/css/bootstrap-datetimepicker.css">
    <!-- Toastr style -->
    <link href="public/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <!-- Gritter -->
    <link href="public/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="public/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link href="public/css/animate.css" rel="stylesheet">
    <link href="public/css/style.css" rel="stylesheet">
    <link href="public/css/mail.css" rel="stylesheet">

    <script type="text/javascript" src="public/js/ajax/ajax.js"></script>
    <!-- jQuery 3.2.1  -->
    <script type="text/javascript" src="public/js/jquery-3.2.1.min.js"></script>
    <!-- Moment  -->
    <script type="text/javascript" src="public/js/moment.min.js"></script>
    <!-- Bootstrap JS -->
    <script type="text/javascript" src="public/plugins/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <!-- Bootstrap Datetimepicker -->
    <script type="text/javascript" src="public/plugins/bootstrap-3.3.7-dist/js/bootstrap-datetimepicker.js"></script>
    <title>Ventanilla Virtual</title>
  </head>
  <body>
    <div class="wraper">
      <?php
        # SIDEBAR
        require_once 'public/layouts/admin/sidebar.php';
      ?>
      <div id="page-wrapper" class="gray-bg dashbard-1">
      <?php
        # HEADER NAVBAR
        require_once 'public/layouts/admin/header.php';

        # SECTION
        require_once 'public/layouts/admin/section.php';
        # FOOTER
        require_once 'public/layouts/admin/footer.php';
      ?>
      </div>
    </div>
    <!-- Mainly scripts -->
    <script src="public/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="public/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="public/js/plugins/flot/jquery.flot.js"></script>
    <script src="public/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="public/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="public/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="public/js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="public/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="public/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="public/js/inspinia.js"></script>
    <script src="public/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="public/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="public/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="public/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="public/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="public/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="public/js/plugins/toastr/toastr.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function () {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 6500
        };
      });
    </script>

  </body>
</html>
