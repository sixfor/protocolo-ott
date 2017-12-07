<?php

  class Conexion extends mysqli {

    private $enlace;

    function __construct ($user_mysql){

      // header('Content-Type: text/html; charset=utf-8');
      try {
        $server = "localhost";
        // $db = "protocolo_ott";
        $db = "a3nh46kd2_ventanilla";
        $user = "";
        $pass = "";
        switch ($user_mysql) {
          case 1:
            $user="a3nh46kd2_root";
            $pass="Tya0.Lpx0ML3";
            // $user = "mikasa";
            // $pass = "acker";
            break;
          case 2:
            $user="a3nh46kd2_anonimo";
            $pass="UleR_xga!.5+";
            // $user = "anonimous";
            // $pass = "1234";
            break;
          case 3:
            $user="a3nh46kd2_editor";
            $pass="uVNlVT1Rn!Pm";
            // $user = "administrador";
            // $pass = "admin";
            break;
        }

        $this->enlace = new mysqli($server, $user, $pass, $db);
        $this->enlace->set_charset("utf8");
        // if ($this->enlace->connect_error) {
        //   die('Error de Conexion ('.$this->enlace->connect_errno.') ' . $this->enlace->connect_error);
        // }

      }
      catch(mysqli_sql_exception $e) {
        http_response_code(500);
        exit;
      }
    }

    public function cud ($sql){

      mysqli_query($this->enlace,$sql);

      if (mysqli_error($this->enlace)){
        return mysqli_errno($this->enlace);
      }
      else {
        return true;
      }

    }

    public function busquedas ($sql){
      $result = mysqli_query($this->enlace, $sql);

      if (!mysqli_error($this->enlace)) {

        $arreglo = array();
        while ($row = mysqli_fetch_array($result)) {
          $arreglo[] = $row;
        }

        return $arreglo;

      }
      else {
        return false;
      }
    }

    public function cerrar () {
      mysqli_close($this->enlace);
    }

  }
?>
