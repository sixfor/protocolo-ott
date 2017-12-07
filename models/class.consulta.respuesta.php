<?php

  class ConsultaRespuesta {

    private $id;
    private $respuesta;
    private $id_consulta;
    private $id_usuario;
    private $fecha;
    private $hora;
    private $estado;

    function __construct($id=null, $respuesta=null, $id_consulta=null, $id_usuario=null, $fecha=null, $hora=null, $estado=null) {
      $this->id = $id;
      $this->respuesta = $respuesta;
      $this->id_consulta = $id_consulta;
      $this->id_usuario = $id_usuario;
      $this->fecha = $fecha;
      $this->hora = $hora;
      $this->estado = $estado;
    }

    function addRespuesta($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "INSERT INTO
                tbl_consulta_respuestas
                (
                  respuesta,
                  id_consulta,
                  id_usuario,
                  fecha,
                  hora,
                  estado
                )
              VALUES
                (
                  '".$this->respuesta."',
                  '".$this->id_consulta."',
                  '".$this->id_usuario."',
                  '".$this->fecha."',
                  '".$this->hora."',
                  '".$this->estado."'
                )";
      $resp = $con->cud($sql);
      $con->cerrar();
      return $resp;
    }

    function getRespuestasConsulta($id_rol) {

      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_consulta_respuestas.id_con_respuesta,
                tbl_consulta_respuestas.respuesta,
                tbl_consulta_respuestas.id_consulta,
                tbl_consulta_respuestas.id_usuario,
                tbl_consulta_respuestas.fecha,
                tbl_consulta_respuestas.hora,
                tbl_consulta_respuestas.estado,
                tbl_uzuaryoz.id_usuario,
                tbl_uzuaryoz.nombre,
                tbl_uzuaryoz.apellido,
                (tbl_roles.nombre) AS nombre_rol
              FROM
                tbl_consulta_respuestas,
                tbl_consultas,
                tbl_uzuaryoz,
                tbl_roles
              WHERE
                tbl_consulta_respuestas.id_consulta = tbl_consultas.id_consulta AND
                tbl_consulta_respuestas.id_usuario = tbl_uzuaryoz.id_usuario AND
                tbl_uzuaryoz.id_rol = tbl_roles.id_rol AND
                tbl_consulta_respuestas.id_consulta = '".$this->id_consulta."' AND
                tbl_consulta_respuestas.estado = '".$this->estado."'
              ORDER BY
                tbl_consulta_respuestas.fecha ASC,
                tbl_consulta_respuestas.hora ASC";
      $resp = $con->busquedas($sql);
      $con->cerrar();
      return $resp;
    }

    function countRespuestasConsulta($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                COUNT(id_con_respuesta) AS cantidad_respuestas
              FROM
                tbl_consulta_respuestas
              WHERE
                id_consulta = '".$this->id_consulta."' AND
                estado = '".$this->estado."'";
      $resp = $con->busquedas($sql);
      $con->cerrar();
      return $resp;
    }

  }


?>
