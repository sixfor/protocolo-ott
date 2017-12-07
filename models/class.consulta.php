<?php

  class Consulta {

    private $id;
    private $fecha;
    private $hora;
    private $id_usuario;
    private $verificador;
    private $solicitud;
    private $leido;
    private $estado;
    private $file;
    private $tiempo_solucion;
    private $problema_oportunidad;
    private $met_prod_serv;
    private $productos_obtener;
    private $proceso_productivo;

    function __construct($id=null, $fecha=null, $hora=null, $id_usuario=null, $verificador=null, $solicitud=null, $leido=null, $estado=null, $file=null, $tiempo_solucion=null, $problema_oportunidad=null, $met_prod_serv=null, $productos_obtener=null, $proceso_productivo=null) {
      $this->id = $id;
      $this->fecha = $fecha;
      $this->hora = $hora;
      $this->id_usuario = $id_usuario;
      $this->verificador = $verificador;
      $this->solicitud = $solicitud;
      $this->leido = $leido;
      $this->estado = $estado;
      $this->file = $file;
      $this->tiempo_solucion = $tiempo_solucion;
      $this->problema_oportunidad= $problema_oportunidad;
      $this->met_prod_serv = $met_prod_serv;
      $this->productos_obtener = $productos_obtener;
      $this->proceso_productivo = $proceso_productivo;
    }

    function leido($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "UPDATE tbl_consultas SET leido = '".$this->leido."' WHERE id_consulta = '".$this->id."'";
      $update = $con->cud($sql);
      $con->cerrar();
      return $update;
    }

    function verificarConsulta($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "UPDATE tbl_consultas SET verificador = '".$this->verificador."' WHERE id_consulta = '".$this->id."'";
      $update = $con->cud($sql);
      $con->cerrar();
      return $update;
    }

    /**
     * Obtiene una consulta (Requerimiento) segun el ID CONSULTA O RUT
     */
    function getConsultaCustom($id_rol, $search) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_consultas.id_consulta,
                tbl_consultas.fecha,
                tbl_consultas.hora,
                tbl_consultas.id_usuario,
                tbl_consultas.verificador,
                tbl_consultas.solicitud,
                tbl_consultas.leido,
                tbl_consultas.estado,
                tbl_consultas.file,
                tbl_consultas.antiguedad_actividad,
                tbl_consultas.problema_oportunidad,
                tbl_consultas.met_prod_serv,
                tbl_consultas.productos_obtener,
                tbl_consultas.proceso_productivo,
                tbl_uzuaryoz.icono_perfil,
                tbl_uzuaryoz.nombre,
                tbl_uzuaryoz.apellido,
                tbl_uzuaryoz.email,
                tbl_uzuaryoz.razon_social,
                tbl_uzuaryoz.rut,
                tbl_uzuaryoz.celular,
                tbl_uzuaryoz.telefono,
                tbl_uzuaryoz.email_opcional
              FROM
                tbl_consultas,
                tbl_uzuaryoz
              WHERE
                tbl_consultas.id_usuario = tbl_uzuaryoz.id_usuario AND
                tbl_consultas.estado = '".$this->estado."' AND
                (tbl_consultas.id_consulta = '".$search."' OR
                tbl_uzuaryoz.rut = '".$search."')
              ORDER BY
                tbl_consultas.leido ASC,
                tbl_consultas.verificador ASC,
                tbl_consultas.fecha DESC,
                tbl_consultas.hora DESC,
                tbl_consultas.id_consulta DESC
              LIMIT 20";
      $resp = $con->busquedas($sql);
      $con->cerrar();
      return $resp;
    }

    /**
     * Obtiene una consulta segun el ID CONSULTA
     */
    function getConsultaUser($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_consultas.id_consulta,
                tbl_consultas.fecha,
                tbl_consultas.hora,
                tbl_consultas.id_usuario,
                tbl_consultas.verificador,
                tbl_consultas.solicitud,
                tbl_consultas.leido,
                tbl_consultas.estado,
                tbl_consultas.file,
                tbl_consultas.antiguedad_actividad,
                tbl_consultas.problema_oportunidad,
                tbl_consultas.met_prod_serv,
                tbl_consultas.productos_obtener,
                tbl_consultas.proceso_productivo,
                tbl_uzuaryoz.icono_perfil,
                tbl_uzuaryoz.nombre,
                tbl_uzuaryoz.apellido,
                tbl_uzuaryoz.email,
                tbl_uzuaryoz.razon_social,
                tbl_uzuaryoz.rut,
                tbl_uzuaryoz.celular,
                tbl_uzuaryoz.telefono,
                tbl_uzuaryoz.email_opcional
              FROM
                tbl_consultas,
                tbl_uzuaryoz
              WHERE
                tbl_consultas.id_usuario = tbl_uzuaryoz.id_usuario AND
                tbl_consultas.estado = '".$this->estado."' AND
                tbl_consultas.id_consulta = '".$this->id."'";
      $resp = $con->busquedas($sql);
      $con->cerrar();
      return $resp;
    }

    /**
     * Obtiene consultas segun ID USUARIO. Si el atributo ID USUARIO es NULL, se obtienen
     * todas las consultas que esten habilitadas
     */
    function getConsultas($id_rol) {
      if (empty($this->id_usuario)) {
        $sql = "SELECT
                  tbl_consultas.id_consulta,
                  tbl_consultas.fecha,
                  tbl_consultas.hora,
                  tbl_consultas.id_usuario,
                  tbl_consultas.verificador,
                  tbl_consultas.solicitud,
                  tbl_consultas.leido,
                  tbl_consultas.estado,
                  tbl_consultas.file,
                  tbl_consultas.antiguedad_actividad,
                  tbl_consultas.problema_oportunidad,
                  tbl_consultas.met_prod_serv,
                  tbl_consultas.productos_obtener,
                  tbl_consultas.proceso_productivo,
                  tbl_uzuaryoz.icono_perfil,
                  tbl_uzuaryoz.nombre,
                  tbl_uzuaryoz.apellido,
                  tbl_uzuaryoz.email,
                  tbl_uzuaryoz.razon_social,
                  tbl_uzuaryoz.rut,
                  tbl_uzuaryoz.celular,
                  tbl_uzuaryoz.telefono,
                  tbl_uzuaryoz.email_opcional
                FROM
                  tbl_consultas,
                  tbl_uzuaryoz
                WHERE
                  tbl_consultas.id_usuario = tbl_uzuaryoz.id_usuario AND
                  tbl_consultas.estado = '".$this->estado."'
                ORDER BY
                  tbl_consultas.leido ASC,
                  tbl_consultas.verificador ASC,
                  tbl_consultas.fecha DESC,
                  tbl_consultas.hora DESC,
                  tbl_consultas.id_consulta DESC
                LIMIT 20";
      }
      else {
        $sql = "SELECT
                  tbl_consultas.id_consulta,
                  tbl_consultas.fecha,
                  tbl_consultas.hora,
                  tbl_consultas.id_usuario,
                  tbl_consultas.verificador,
                  tbl_consultas.solicitud,
                  tbl_consultas.leido,
                  tbl_consultas.estado,
                  tbl_consultas.file,
                  tbl_consultas.antiguedad_actividad,
                  tbl_consultas.problema_oportunidad,
                  tbl_consultas.met_prod_serv,
                  tbl_consultas.productos_obtener,
                  tbl_consultas.proceso_productivo,
                  tbl_uzuaryoz.icono_perfil,
                  tbl_uzuaryoz.nombre,
                  tbl_uzuaryoz.apellido,
                  tbl_uzuaryoz.email,
                  tbl_uzuaryoz.razon_social,
                  tbl_uzuaryoz.rut,
                  tbl_uzuaryoz.celular,
                  tbl_uzuaryoz.telefono,
                  tbl_uzuaryoz.email_opcional
                FROM
                  tbl_consultas,
                  tbl_uzuaryoz
                WHERE
                  tbl_consultas.id_usuario = tbl_uzuaryoz.id_usuario AND
                  tbl_consultas.estado = '".$this->estado."' AND
                  tbl_consultas.id_usuario = '".$this->id_usuario."'
                ORDER BY
                  tbl_consultas.leido ASC,
                  tbl_consultas.verificador ASC,
                  tbl_consultas.fecha DESC,
                  tbl_consultas.hora DESC,
                  tbl_consultas.id_consulta DESC";
      }
      $con = new Conexion($id_rol);
      $consultas = $con->busquedas($sql);
      $con->cerrar();
      return $consultas;
    }

    /**
     * Crea una consulta
     *
     * @param ROL del usuario, solo si el usuario tiene permisos puede insertar la consulta
     * @return TRUE si la consulta fue registrada correctamente, si no, el codigo de error
     */
    function newConsulta($id_rol) {
      $sql = "INSERT INTO
                tbl_consultas
                (
                  fecha,
                  hora,
                  id_usuario,
                  verificador,
                  solicitud,
                  leido,
                  estado,
                  antiguedad_actividad,
                  problema_oportunidad,
                  met_prod_serv,
                  productos_obtener,
                  proceso_productivo
                )
              VALUES
                (
                  '".$this->fecha."',
                  '".$this->hora."',
                  '".$this->id_usuario."',
                  '".$this->verificador."',
                  '".$this->solicitud."',
                  0,
                  1,
                  '".$this->tiempo_solucion."',
                  '".$this->problema_oportunidad."',
                  '".$this->met_prod_serv."',
                  '".$this->productos_obtener."',
                  '".$this->proceso_productivo."'
                );";
      $con = new Conexion($id_rol);
      $consulta = $con->cud($sql);
      $con->cerrar();
      return $consulta;
    }
    /**
     * Cuenta las consultas que tiene el usuario. Esta funcion dependera de los atributos
     * ID Usuario, Leido y Estado.
     *
     * @param ROL del usuario
     * @return Cantidad de consultas
     */
    function countConsultasUser($id_rol) {
      $sql = "SELECT count(id_consulta) AS consultas FROM tbl_consultas WHERE id_usuario = '".$this->id_usuario."' AND leido = '".$this->leido."' AND estado = '".$this->estado."'";
      $con = new Conexion($id_rol);
      $resp = $con->busquedas($sql);
      $con->cerrar();
      return $resp;
    }

    /**
     * Función que modifica el valor del campo file de la consulta
     */
    function updateFile($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "UPDATE
                tbl_consultas
              SET
                file = '".$this->file."'
              WHERE
                id_consulta = '".$this->id."'";
      $update = $con->cud($sql);
      $con->cerrar();
      return $update;
    }

    /**
     * Obtiene el numero de ID del requerimiento que será ingresado
     *
     * @return ID Autoincrement
     */
    function getProximoId($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT auto_increment FROM information_schema.tables WHERE table_schema = 'a3nh46kd2_ventanilla' AND table_name='tbl_consultas'";
      $id = $con->busquedas($sql);
      $con->cerrar();
      return $id;
    }

  }

?>
