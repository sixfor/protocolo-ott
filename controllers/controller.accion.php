<?php

  /**
   * Funcion que obtiene las acciones que puede utilizar en el modulo
   */
  function getAccionesOnModulo($id_rol, $id_modulo) {
    $accion = new Accion(null, null, null, 1);
    return $accion->getAccionesOnModulo($id_rol, $id_modulo);
  }

  /**
   * Función que se obtiene las acciones que puede utilizar un usuario segun su ID ROL
   *
   * @param $id_rol => Hace referencia al tipo de rol que tiene el usuario
   * @return Array con las acciones que puede utilizar
   */
   function getAccionesUser($id_rol) {
     # Establecemos el ESTADO en 1
     $accion = new Accion(null, null, null, 1);
     return $accion->getAccionesUser($id_rol);
   }
?>
