<?php


  class Usuario {

    private $id;
    private $nombre;
    private $apellido;
    private $email;
    private $password;
    private $id_rol;
    private $razon_social;
    private $rut;
    private $direccion;
    private $comuna;
    private $telefono;
    private $celular;
    private $email_empresa;
    private $host_conexion;
    private $fecha_conexion;
    private $hora_conexion;
    private $estado;
    private $icono_perfil;
    private $sector_economico;
    private $email_opcional;

    function __construct($id=null, $nombre=null, $apellido=null, $email=null, $password=null, $id_rol=null, $razon_social=null, $rut=null, $direccion=null, $comuna=null, $telefono=null, $celular=null, $email_empresa=null, $host_conexion=null, $fecha_conexion=null, $hora_conexion=null, $estado=null, $icono_perfil=null, $sector_economico=null, $email_opcional=null) {
      $this->setId($id);
      $this->setNombre($nombre);
      $this->setApellido($apellido);
      $this->setEmail($email);
      $this->setPassword($password);
      $this->setIdRol($id_rol);
      $this->setRazonSocial($razon_social);
      $this->setRut($rut);
      $this->setDireccion($direccion);
      $this->setComuna($comuna);
      $this->setTelefono($telefono);
      $this->setCelular($celular);
      $this->setEmailEmpresa($email_empresa);
      $this->setHostConexion($host_conexion);
      $this->setFechaConexion($fecha_conexion);
      $this->setHoraConexion($hora_conexion);
      $this->setEstado($estado);
      $this->setIconoPerfil($icono_perfil);
      $this->setSectorEconomico($sector_economico);
      $this->setEmailOpcional($email_opcional);
    }

    function setId($id) {
      $this->id = $id;
    }
    function setNombre($nombre) {
      $this->nombre = $nombre;
    }
    function setApellido($apellido) {
      $this->apellido = $apellido;
    }
    function setEmail($email) {
      $this->email = $email;
    }
    function setPassword($password) {
      $this->password = $password;
    }
    function setIdRol($id_rol) {
      $this->id_rol = $id_rol;
    }
    function setRazonSocial($razon_social) {
      $this->razon_social = $razon_social;
    }
    function setRut($rut) {
      $this->rut = $rut;
    }
    function setDireccion($direccion) {
      $this->direccion = $direccion;
    }
    function setComuna($comuna) {
      $this->comuna = $comuna;
    }
    function setTelefono($telefono) {
      $this->telefono = $telefono;
    }
    function setCelular($celular) {
      $this->celular = $celular;
    }
    function setEmailEmpresa($email_empresa) {
      $this->email_empresa = $email_empresa;
    }
    function setHostConexion($host_conexion) {
      $this->host_conexion = $host_conexion;
    }
    function setFechaConexion($fecha_conexion) {
      $this->fecha_conexion = $fecha_conexion;
    }
    function setHoraConexion($hora_conexion) {
      $this->hora_conexion = $hora_conexion;
    }
    function setEstado($estado) {
      $this->estado = $estado;
    }
    function setIconoPerfil($icono_perfil) {
      $this->icono_perfil = $icono_perfil;
    }
    function setSectorEconomico($sector_economico) {
      $this->sector_economico = $sector_economico;
    }
    function setEmailOpcional($email_opcional) {
      $this->email_opcional = $email_opcional;
    }

    function getId() {
      return $this->id;
    }
    function getNombre() {
      return $this->nombre;
    }
    function getApellido() {
      return $this->apellido;
    }
    function getEmail() {
      return $this->email;
    }
    function getPassword() {
      return $this->password;
    }
    function getIdRol() {
      return $this->id_rol;
    }
    function getRazonSocial() {
      return $this->razon_social;
    }
    function getRut() {
      return $this->rut;
    }
    function getDireccion() {
      return $this->direccion;
    }
    function getComuna() {
      return $this->comuna;
    }
    function getTelefono() {
      return $this->telefono;
    }
    function getCelular() {
      return $this->celular;
    }
    function getEmailEmpresa() {
      return $this->email_empresa;
    }
    function getHostConexion() {
      return $this->host_conexion;
    }
    function getFechaConexion() {
      return $this->fecha_conexion;
    }
    function getHoraConexion() {
      return $this->hora_conexion;
    }
    function getEstado() {
      return $this->estado;
    }
    function getIconoPerfil() {
      return $this->icono_perfil;
    }
    function getSectorEconomico() {
      return $this->sector_economico;
    }
    function getEmailOpcional() {
      return $this->email_opcional;
    }

    function getResetPasswordByEmail($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT * FROM tbl_password_reset WHERE email = '".$this->getEmail()."'";
      $select = $con->busquedas($sql);
      $con->cerrar();
      return $select;
    }

    function getResetPassword($id_rol, $token) {
      $con = new Conexion($id_rol);
      $sql = "SELECT * FROM tbl_password_reset WHERE email = '".$this->getEmail()."' AND token = '".$token."'";
      $select = $con->busquedas($sql);
      $con->cerrar();
      return $select;
    }

    function createPassword($id_rol, $token, $estado) {
      $con = new Conexion($id_rol);
      $sql = "INSERT INTO tbl_password_reset (email, token, estado) VALUES ('".$this->getEmail()."', '".$token."', '".$estado."');";
      $insert = $con->cud($sql);
      $con->cerrar();
      return $insert;
    }

    function changeStatusTokenPassword($id_rol, $estado, $token) {
      $con = new Conexion($id_rol);
      $sql = "UPDATE tbl_password_reset SET estado = '".$estado."' WHERE email = '".$this->getEmail()."' AND token = '".$token."'";
      $update = $con->cud($sql);
      $con->cerrar();
      return $update;
    }

    function changeTokenPassword($id_rol, $token, $estado) {
      $con = new Conexion($id_rol);
      $sql = "UPDATE tbl_password_reset SET token = '".$token."', estado = '".$estado."' WHERE email = '".$this->getEmail()."'";
      $update = $con->cud($sql);
      $con->cerrar();
      return $update;
    }

    function changePassword($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "UPDATE tbl_uzuaryoz SET password = '".$this->getPassword()."' WHERE email = '".$this->getEmail()."'";
      $update = $con->cud($sql);
      $con->cerrar();
      return $update;
    }

    function getUserByEmail($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_uzuaryoz.id_usuario,
                tbl_uzuaryoz.nombre,
                tbl_uzuaryoz.apellido,
                tbl_uzuaryoz.email,
                tbl_uzuaryoz.password,
                tbl_uzuaryoz.id_rol,
                tbl_uzuaryoz.razon_social,
                tbl_uzuaryoz.rut,
                tbl_uzuaryoz.direccion,
                tbl_uzuaryoz.comuna,
                tbl_uzuaryoz.telefono,
                tbl_uzuaryoz.celular,
                tbl_uzuaryoz.email_empresa,
                tbl_uzuaryoz.host_conexion,
                tbl_uzuaryoz.fecha_conexion,
                tbl_uzuaryoz.hora_conexion,
                tbl_uzuaryoz.estado,
                tbl_uzuaryoz.icono_perfil,
                (tbl_roles.nombre) AS nombre_rol,
                tbl_uzuaryoz.email_opcional
              FROM
                tbl_uzuaryoz,
                tbl_roles
              WHERE
                tbl_uzuaryoz.id_rol = tbl_roles.id_rol AND
                (tbl_uzuaryoz.email = '".$this->getEmail()."' OR tbl_uzuaryoz.email_opcional = '".$this->getEmailOpcional()."')
              LIMIT 1";
      $select = $con->busquedas($sql);
      $con->cerrar();
      return $select;
    }

    function deleteUser($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "UPDATE tbl_uzuaryoz SET estado = 0 WHERE id_usuario = '".$this->getId()."'";
      $delete = $con->cud($sql);
      $con->cerrar();
      return $delete;
    }

    function updateUser($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "UPDATE
                tbl_uzuaryoz
              SET
                ".( !empty($this->getPassword()) ? 'password = "'.$this->getPassword().'",' : "" )."
                nombre = '".$this->getNombre()."',
                apellido = '".$this->getApellido()."',
                email = '".$this->getEmail()."',
                email_opcional = '".$this->getEmailOpcional()."',
                razon_social = '".$this->getRazonSocial()."',
                rut = '".$this->getRut()."',
                sector_economico = '".$this->getSectorEconomico()."',
                direccion = '".$this->getDireccion()."',
                comuna = '".$this->getComuna()."',
                telefono = '".$this->getTelefono()."',
                celular = '".$this->getCelular()."',
                email_empresa = '".$this->getEmailEmpresa()."'
              WHERE
                id_usuario = '".$this->getId()."'";
      $update = $con->cud($sql);
      $con->cerrar();
      return $update;
      // return $sql;
    }

    function getUserByRut($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_uzuaryoz.id_usuario,
                tbl_uzuaryoz.nombre,
                tbl_uzuaryoz.apellido,
                tbl_uzuaryoz.email,
                tbl_uzuaryoz.id_rol,
                tbl_uzuaryoz.razon_social,
                tbl_uzuaryoz.rut,
                tbl_uzuaryoz.direccion,
                tbl_uzuaryoz.comuna,
                tbl_uzuaryoz.telefono,
                tbl_uzuaryoz.celular,
                tbl_uzuaryoz.email_empresa,
                tbl_uzuaryoz.host_conexion,
                tbl_uzuaryoz.fecha_conexion,
                tbl_uzuaryoz.hora_conexion,
                tbl_uzuaryoz.estado,
                tbl_uzuaryoz.icono_perfil,
                (tbl_roles.nombre) AS nombre_rol,
                tbl_uzuaryoz.email_opcional
              FROM
                tbl_uzuaryoz,
                tbl_roles
              WHERE
                tbl_uzuaryoz.id_rol = tbl_roles.id_rol AND
                tbl_uzuaryoz.rut LIKE '%".$this->getRut()."%'";
      $resp = $con->busquedas($sql);
      $con->cerrar();
      return $resp;
      // return $sql;
    }

    function getUser($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_uzuaryoz.id_usuario,
                tbl_uzuaryoz.nombre,
                tbl_uzuaryoz.apellido,
                tbl_uzuaryoz.email,
                tbl_uzuaryoz.id_rol,
                tbl_uzuaryoz.razon_social,
                tbl_uzuaryoz.rut,
                tbl_uzuaryoz.direccion,
                tbl_uzuaryoz.comuna,
                tbl_uzuaryoz.telefono,
                tbl_uzuaryoz.celular,
                tbl_uzuaryoz.email_empresa,
                tbl_uzuaryoz.host_conexion,
                tbl_uzuaryoz.fecha_conexion,
                tbl_uzuaryoz.hora_conexion,
                tbl_uzuaryoz.estado,
                tbl_uzuaryoz.icono_perfil,
                (tbl_roles.nombre) AS nombre_rol,
                tbl_uzuaryoz.email_opcional
              FROM
                tbl_uzuaryoz,
                tbl_roles
              WHERE
                tbl_uzuaryoz.id_rol = tbl_roles.id_rol AND
                tbl_uzuaryoz.id_usuario = '".$this->getId()."'";
      $resp = $con->busquedas($sql);
      $con->cerrar();
      return $resp;
    }

    function getUsers($id_rol) {
      $con = new Conexion($id_rol);
      $sql = "SELECT
                tbl_uzuaryoz.id_usuario,
                tbl_uzuaryoz.nombre,
                tbl_uzuaryoz.apellido,
                tbl_uzuaryoz.email,
                tbl_uzuaryoz.id_rol,
                tbl_uzuaryoz.razon_social,
                tbl_uzuaryoz.rut,
                tbl_uzuaryoz.direccion,
                tbl_uzuaryoz.comuna,
                tbl_uzuaryoz.telefono,
                tbl_uzuaryoz.celular,
                tbl_uzuaryoz.email_empresa,
                tbl_uzuaryoz.host_conexion,
                tbl_uzuaryoz.fecha_conexion,
                tbl_uzuaryoz.hora_conexion,
                tbl_uzuaryoz.estado,
                tbl_uzuaryoz.icono_perfil,
                (tbl_roles.nombre) AS nombre_rol,
                tbl_uzuaryoz.email_opcional
              FROM
                tbl_uzuaryoz,
                tbl_roles
              WHERE
                tbl_uzuaryoz.id_rol = tbl_roles.id_rol AND tbl_uzuaryoz.estado = 1";
      $resp = $con->busquedas($sql);
      $con->cerrar();
      return $resp;
    }

    /**
     * Función que se encarga de obtener los datos del usuario mediante el Login
     *
     * @return Un arreglo con los datos del usuario en caso de existir
     */
    function getUserLogin() {
      $con = new Conexion(2);
      $sql = "SELECT
                tbl_uzuaryoz.id_usuario,
                tbl_uzuaryoz.nombre,
                tbl_uzuaryoz.apellido,
                tbl_uzuaryoz.email,
                tbl_uzuaryoz.id_rol,
                tbl_uzuaryoz.razon_social,
                tbl_uzuaryoz.rut,
                tbl_uzuaryoz.direccion,
                tbl_uzuaryoz.comuna,
                tbl_uzuaryoz.telefono,
                tbl_uzuaryoz.celular,
                tbl_uzuaryoz.email_empresa,
                tbl_uzuaryoz.host_conexion,
                tbl_uzuaryoz.fecha_conexion,
                tbl_uzuaryoz.hora_conexion,
                tbl_uzuaryoz.estado,
                tbl_uzuaryoz.icono_perfil,
                (tbl_roles.nombre) AS nombre_rol,
                tbl_uzuaryoz.email_opcional
              FROM
                tbl_uzuaryoz,
                tbl_roles
              WHERE
                tbl_uzuaryoz.id_rol = tbl_roles.id_rol AND
                (tbl_uzuaryoz.email = '".$this->getEmail()."' OR tbl_uzuaryoz.email_opcional = '".$this->getEmailOpcional()."') AND
                tbl_uzuaryoz.password = '".$this->getPassword()."' AND
                tbl_uzuaryoz.estado = 1
              LIMIT 1";
      $userLogin = $con->busquedas($sql);
      $con->cerrar();
      return $userLogin;
      // return $sql;
    }

    /**
     * Función que se encarga de insertar un nuevo usuario
     *
     * @return TRUE si lo inserta, sino retornara un código de error.
     */
    function addUser() {
      $con = new Conexion(1);
      $sql = "INSERT INTO tbl_uzuaryoz
              (
                nombre,
                apellido,
                email,
                password,
                id_rol,
                razon_social,
                rut,
                direccion,
                comuna,
                telefono,
                celular,
                email_empresa,
                estado,
                sector_economico,
                email_opcional
              )
              VALUES
              (
                '".$this->getNombre()."',
                '".$this->getApellido()."',
                '".$this->getEmail()."',
                '".$this->getPassword()."',
                '".$this->getIdRol()."',
                '".$this->getRazonSocial()."',
                '".$this->getRut()."',
                '".$this->getDireccion()."',
                '".$this->getComuna()."',
                '".$this->getTelefono()."',
                '".$this->getCelular()."',
                '".$this->getEmailEmpresa()."',
                '".$this->getEstado()."',
                '".$this->getSectorEconomico()."',
                '".$this->getEmailOpcional()."'
              );";
      $register = $con->cud($sql);
      $con->cerrar();
      return $register;
      // return $sql;
    }

    /**
     * Función que se encarga de obtener el ID del registro que se va a insertar
     *
     * @return Un arreglo con el ID del usuario que se va a insertar
     */
     function getProximoId() {
       $con = new Conexion(1);
       $sql = "SELECT auto_increment FROM information_schema.tables WHERE table_schema = 'a3nh46kd2_ventanilla' AND table_name='tbl_uzuaryoz'";
      //  $sql = "SELECT auto_increment FROM information_schema.tables WHERE table_schema = 'protocolo_ott' AND table_name='tbl_uzuaryoz'";
       $id = $con->busquedas($sql);
       $con->cerrar();
       return $id;
     }

     /**
      * Función que actualiza la fecha, hora y host de conexión que esta utilizando
      * el usuario al momento de loguearse.
      *
      */
      function updateDateSession() {
        $con = new Conexion(1);
        $sql = "UPDATE
                  tbl_uzuaryoz
                SET
                  host_conexion = '".$this->getHostConexion()."',
                  fecha_conexion = '".$this->getFechaConexion()."',
                  hora_conexion = '".$this->getHoraConexion()."'
                WHERE
                  id_usuario = '".$this->getId()."';";
        $update = $con->cud($sql);
        $con->cerrar();
        return $sql;
      }

  }


?>
