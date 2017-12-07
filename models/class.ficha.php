<?php


  class Ficha {

    private $id;
    private $id_usuario;
    private $fecha_origen;
    private $ant_act;
    private $sector_eco;
    private $solicitud;
    private $estado;

    function __construct($id=null, $fecha_origen=null, $id_usuario=null, $ant_act=null, $sector_eco=null, $solicitud=null, $estado=null) {
      $this->setId($id);
      $this->setIdUsuario($id_usuario);
      $this->setFechaOrigen($fecha_origen);
      $this->setAntAct($ant_act);
      $this->setSectorEco($sector_eco);
      $this->setSolicitud($solicitud);
      $this->setEstado($estado);
    }

    function setId($id) {
      $this->id = $id;
    }
    function setIdUsuario($id_usuario) {
      $this->id_usuario = $id_usuario;
    }
    function setFechaOrigen($fecha_origen) {
      $this->fecha_origen = $fecha_origen;
    }
    function setAntAct($ant_act) {
      $this->ant_act = $ant_act;
    }
    function setSectorEco($sector_eco) {
      $this->sector_eco = $sector_eco;
    }
    function setSolicitud($solicitud) {
      $this->solicitud = $solicitud;
    }
    function setEstado($estado) {
      $this->estado = $estado;
    }

    function getId() {
      return $this->id;
    }
    function getIdUsuario() {
      return $this->id_usuario;
    }
    function getFechaOrigen() {
      return $this->fecha_origen;
    }
    function getAntAct() {
      return $this->ant_act;
    }
    function getSectorEco() {
      return $this->sector_eco;
    }
    function getSolicitud() {
      return $this->solicitud;
    }
    function getEstado() {
      return $this->estado;
    }

    public function addFichaSuscripcion() {
      $con = new Conexion(1);
      $sql = "INSERT INTO tbl_fichas
              (
                id_usuario,
                fecha_origen,
                antiguedad_actividad,
                sector_economico,
                solicitud_apoyo,
                estado
              )
              VALUES
              (
                '".$this->getIdUsuario()."',
                '".$this->getFechaOrigen()."',
                '".$this->getAntAct()."',
                '".$this->getSectorEco()."',
                '".$this->getSolicitud()."',
                '".$this->getEstado()."'
              );";
      $resp = $con->cud($sql);
      $con->cerrar();
      return $resp;
    }

  }


?>
