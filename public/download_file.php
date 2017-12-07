<?php

  require_once '../ack/ack.php';
  require_once '../models/class.conexion.php';
  require_once '../controllers/controller.filtro.php';

  if (isset($_GET["c"]) && !empty($_GET["c"]) && isset($_GET["u"]) && !empty($_GET["u"]) && isset($_GET["f"]) && !empty($_GET["f"])) {

    session_start();
    $file = descifrar(trim($_GET["f"]), KEY_ENCRYPT_ALL);
    $source = 'documents/' . $file;

    header('Content-Disposition: attachment; filename='.$file);
    header('Content-Type: application/octet-stream');
    header('Content-Transfer-Enconding: binary');
    header('Content-Lenght:' . filesize($source));

    if ($_SESSION["user_ott"][0]["id_rol"] == 1 || $_SESSION["user_ott"][0]["id_rol"] == 3) {
      readfile($source);
    }
    else {
      $con = new Conexion($_SESSION["user_ott"][0]["id_rol"]);

      $id_consulta = descifrar(trim($_GET["c"]), KEY_ENCRYPT_ALL);
      $id_usuario = descifrar(trim($_GET["u"]), KEY_ENCRYPT_ALL);

      $sql = "SELECT file FROM tbl_consultas WHERE id_consulta = '".$id_consulta."' AND id_usuario = '".$id_usuario."' LIMIT 1";
      $resp = $con->cud($sql);

      if ($resp === true) {
        readfile($source);
      }
      else {
        header('location: index.php?m=1');
      }
    }
  }
  else {
    header('location: index.php?m=2');
  }


?>
