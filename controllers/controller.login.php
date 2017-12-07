<?php


  # Si ocurre un submit con las siguientes variables
  if (isset($_POST["login"]) && isset($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {

    require_once 'controllers/controller.filtro.php';
    require_once 'models/class.user.php';

    # Limpieza de las variables contra ataques
    $email = limpiar(trim($_POST["email"]));
    $password = strtolower($_POST["password"]);
    // $password = limpiar_clave($_POST["password"]);

    $errors = array(
      'credenciales' => 'Email o contraseña incorrectos.',
      'isql' => 'Ha ocurrido un error, intentelo nuevamente.'
    );

    if ($email == strtolower($_POST["email"]) && $password == strtolower($_POST["password"])) {

      $user = new Usuario();
      $password_encrypt = encriptar($_POST["password"], KEY_ENCRYPT);
      # Setea los atributos que se necesitan para evaluar si el usuario existe
      $user->setEmail(strtolower(trim($_POST["email"])));
      $user->setEmailOpcional(strtolower(trim($_POST["email"])));
      // $user->setPassword($_POST["password"]);
      $user->setPassword($password_encrypt);
      # Obtiene un arreglo con los datos del usuario si existe
      $userLogin = $user->getUserLogin();
      # Si el usuario existe, '$userLogin' sera un arreglo con datos dentro
      if (count($userLogin) > 0) {

        # Establece la zona horaria
        date_default_timezone_set('America/Santiago');
        # Establece los datos necesarios para ejecutar una actualización de Login
        $user->setId($userLogin[0]["id_usuario"]);
        $user->setHostConexion($_SERVER['SERVER_ADDR']);
        $user->setFechaConexion(date('Y-m-d'));
        $user->setHoraConexion(date('H:i:s'));
        # Actualiza los datos de conexión
        $user->updateDateSession();

        session_start();
        $_SESSION["user_ott"] = $userLogin;
        // header('location: index.php');
      }
      else {
        # Credenciales incorrecta o el usuario no existe
        $show_error = $errors['credenciales'];
      }
    }
    else {
      # Posible Inyección SQL
      $show_error = $errors['isql'];
    }
  }

?>
