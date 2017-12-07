<?php

  class Modulo {

    private $id;
    private $nombre;
    private $estado;

    function __construct($id=null, $nombre=null, $estado=null) {
      $this->id = $id;
      $this->nombre = $nombre;
      $this->estado = $estado;
    }

    /**
     * Función que obtiene los modulos segun el ID ROL del usuario
     *
     * @return Array con todos los modulos del usuario
     */
    function getModulosUser($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_modulos.id_modulo,
                tbl_modulos.nombre,
                tbl_modulos.estado,
                tbl_modulos.icono
              FROM
                tbl_modulos,
                tbl_roles,
                tbl_rol_modulo
              WHERE
                tbl_modulos.id_modulo = tbl_rol_modulo.id_modulo AND
                tbl_roles.id_rol = tbl_rol_modulo.id_rol AND
                tbl_rol_modulo.id_rol = '".$id_rol."' AND
                tbl_rol_modulo.estado = '".$this->estado."'";
      $modulos = $con->busquedas($sql);
      $con->cerrar();
      return $modulos;
    }


  }

?>
