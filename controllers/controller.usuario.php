<?php

  /**
   * Función que obtiene los datos del usuario que esta logueado
   *
   * @param $id => ID del usuario logueado
   * @return Array con los datos escenciales para el usuario, si no existe retornara FALSE
   */
  function getUser($id, $rol) {
    $user = new Usuario();
    # Si el ID no esta vacío
    if (!empty($id)) {
      $user->setId($id);
      return $user->getUser($rol);
    }
    return false;
  }

  /**
   * Función que ovtiene los datos de todos los usuarios
   *
   * @return Array con todos los usuarios y sus datos
   */
  function getUsers($rol) {
    $user = new Usuario();
    return $user->getUsers($rol);
  }



?>
