<?php

  switch (isset($_GET["a"]) ? $_GET["a"] : null) {

    # MODULO CONSULTA
    case 1:
      require_once 'public/layouts/admin/nueva_consulta.php';
      break;
    case 2:
      require_once 'public/layouts/admin/show_consultas.php';
      break;
    case 3:
      require_once 'public/layouts/admin/conv_consulta.php';
      break;

    # MODULO PERFIL
    case 8:
      require_once 'public/layouts/admin/ver_perfil.php';
      break;
    case 9:
      require_once 'public/layouts/admin/cambiar_clave.php';
      break;

    # MODULO USUARIO
    case 11:
      require_once 'public/layouts/admin/nuevo_usuario.php';
      break;
    case 12:
      require_once 'public/layouts/admin/modificar_usuario.php';
      break;
    case 13:
      require_once 'public/layouts/admin/usuarios.php';
      break;

    default:
      require_once 'public/layouts/admin/welcome.php';
      break;

  }


?>
