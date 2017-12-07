<?php

  class Accion {

    private $id;
    private $nombre;
    private $icono;
    private $estado;

    function __construct($id=null, $nombre=null, $icono=null, $estado=null) {
      $this->id = $id;
      $this->nombre = $nombre;
      $this->icono = $icono;
      $this->estado = $estado;
    }

    function getAccionesOnModulo($id_rol, $id_modulo) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_acciones.id_accion,
                tbl_acciones.nombre,
                tbl_acciones.estado,
                tbl_acciones.icono,
                tbl_permisos.id_modulo
              FROM
                tbl_acciones,
                tbl_roles,
                tbl_permisos
              WHERE
                tbl_acciones.id_accion = tbl_permisos.id_accion AND
                tbl_roles.id_rol = tbl_permisos.id_rol AND
                tbl_permisos.id_rol = '".$id_rol."' AND
                tbl_permisos.estado = '".$this->estado."' AND
                tbl_permisos.id_modulo = '".$id_modulo."'";
      $resp = $con->busquedas($sql);
      $con->cerrar();
      return $resp;
    }

    /**
     * Función que se obtiene las acciones que puede utilizar un usuario segun su ID ROL
     *
     * @param $id_rol => Hace referencia al tipo de rol que tiene el usuario
     * @return Array con las acciones que puede utilizar
     */
    function getAccionesUser($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_acciones.id_accion,
                tbl_acciones.nombre,
                tbl_acciones.estado,
                tbl_acciones.icono,
                tbl_permisos.id_modulo
              FROM
                tbl_acciones,
                tbl_roles,
                tbl_permisos
              WHERE
                tbl_acciones.id_accion = tbl_permisos.id_accion AND
                tbl_roles.id_rol = tbl_permisos.id_rol AND
                tbl_permisos.id_rol = '".$id_rol."' AND
                tbl_permisos.estado = '".$this->estado."'";
      $acciones = $con->busquedas($sql);
      $con->cerrar();
      return $acciones;
    }

  }


?>
