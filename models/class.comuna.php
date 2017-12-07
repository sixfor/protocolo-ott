<?php


  class Comuna {

    private $id;
    private $nombre;

    function __construct($id=null, $nombre=null) {
      $this->id = $id;
      $this->nombre = $nombre;
    }

    function getComunas() {
      $con = new Conexion(2);
      $sql = "SELECT * FROM tbl_comunas";
      return $con->busquedas($sql);
    }

  }


?>
