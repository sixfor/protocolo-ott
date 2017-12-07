<?php

  error_reporting(E_ALL);

  require_once 'ack/ack.php';
  require_once 'models/class.conexion.php';
  require_once 'models/class.user.php';
  require_once 'controllers/controller.filtro.php';
  require_once 'controllers/controller.functions.php';
  require_once 'controllers/controller.password.create.php';
  require_once 'controllers/controller.login.php';

  session_start();


  if (isset($_SESSION["user_ott"])) {
    header('location: index.php');
  }
  else {
  ?>
  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- Bootstrap 3.3.7 -->
      <link rel="stylesheet" href="public/plugins/bootstrap-3.3.7-dist/css/bootstrap.css">
      <!-- Font-awesome -->
      <link href="public/font-awesome/css/font-awesome.css" rel="stylesheet">
      <!-- Toastr style -->
      <link href="public/css/plugins/toastr/toastr.min.css" rel="stylesheet">

      <link href="public/css/animate.css" rel="stylesheet">
      <link href="public/css/style.css" rel="stylesheet">
      <!-- Login CSS -->
      <link rel="stylesheet" href="public/css/login.css">

      <?php
        if (isset($_GET["r"]) && $_GET["r"] == "register")
          echo '<link rel="stylesheet" href="public/css/form.css">';
      ?>

      <!-- Mainly scripts -->
      <script src="public/js/jquery-3.2.1.min.js"></script>
      <script src="public/js/bootstrap.min.js"></script>

      <!-- Alert FORM JS -->
      <script src="public/js/alert.js" charset="utf-8"></script>
      <!-- Toastr -->
      <script src="public/js/plugins/toastr/toastr.min.js"></script>

      <script src="public/js/ajax/ajax.js" charset="utf-8"></script>
      <script src="public/js/ajax/authentication.js" charset="utf-8"></script>
      <title>Bienvenido | Protocolo OTT</title>
    </head>
    <body>
      <div class="container">
        <?php

        switch (isset($_GET["r"]) ? $_GET["r"] : null) {

          case 'register':
            require_once 'public/layouts/authentication/registrarme.php';
            break;

          case 'password_create':
            if (isset($_GET["email"]) && !empty($_GET["email"]) && isset($_GET["token"]) && !empty($_GET["token"]) && isset($_GET["expdate"]) && !empty($_GET["expdate"]))
              require_once 'public/layouts/authentication/password_create.php';
            else
              header('location: 404.html');
            break;

          case 'password_forget':
            require_once 'public/layouts/authentication/password_forget.php';
            break;

          case 'success_send':
            require_once 'public/layouts/authentication/success_password_reset.php';
            break;

          default:
            require_once 'public/layouts/authentication/form_login.php';
            break;
        }
        ?>
      </div>
      <script type="text/javascript">
        $(document).ready(function () {
          toastr.options = {
              closeButton: true,
              progressBar: true,
              showMethod: 'slideDown',
              timeOut: 8000
          };
        });
      </script>
    </body>
  </html>
  <?php
  }
?>
