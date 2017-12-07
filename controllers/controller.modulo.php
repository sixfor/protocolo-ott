<?php

/**
* Función que crea el sidebar del usuario segun su ID ROL
*/
function sidebarUser($id_rol) {

  $sidebar = '';
  $modulos = getModulosUser($id_rol);
  $acciones = getAccionesUser($id_rol);
  # Si se agregan acciones a este usuario se deben agregar a la excepción
  $exceptionAccion = array(1, 3, 4, 5, 6, 7, 10, 12, 14);
  if (count($modulos) > 0) {

    foreach ($modulos as $key => $value) {
      $sidebar .= '<li>
        <a href="#">'.$value["icono"].' <span class="nav-label text-capitalize">'.$value['nombre'].'</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">';
      if (count($acciones) > 0) {
        foreach ($acciones as $keyAccion => $valueAccion) {
          if ($valueAccion["id_modulo"] == $value["id_modulo"] && !in_array($valueAccion["id_accion"], $exceptionAccion)) {
            $sidebar .= '<li><a href="?m='.$value["id_modulo"].'&a='.$valueAccion["id_accion"].'" class="text-capitalize">'.ucfirst($valueAccion['nombre']).'</a></li>';
          }
        }
      }
      $sidebar .= '</ul></li>';
    }

  }
  return $sidebar;
}

/**
* Función que se encarga de obtener los modulos según el usuario que esté
* logueado (ID ROL) y el estado del modulo sea 1 (habilitado).
*
* @param $id_rol => Hace referencia al tipo ROL que tiene el usuario
* @return Array con los modulos si es que el usuario tiene los permisos.
*/
function getModulosUser($id_rol) {
  # Establecemos el ESTADO en 1
  $modulo = new Modulo(null, null, 1);
  # Establece el ID ROL y obtiene modulos
  return $modulo->getModulosUser($id_rol);
}

?>
