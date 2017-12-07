<?php

 function getMeses() {
   $meses = array(
              1   => "enero",
              2   => "febrero",
              3   => "marzo",
              4   => "abril",
              5   => "mayo",
              6   => "junio",
              7   => "julio",
              8   => "agosto",
              9   => "septiembre",
              10  => "octubre",
              11  => "noviembre",
              12  => "diciembre"
            );
   return $meses;
 }

 function getComunas() {
   $comunas = array("corral", "lanco", "los lagos", "mariquina", "máfil", "paillaco", "panguipulli", "valdivia", "futrono", "la unión", "lago ranco", "río bueno");
   return $comunas;
 }

 function intervalDateTime($fecha, $hora) {
   $datetime =  new DateTime($fecha . ' ' . $hora);
   $datetime_actual = new DateTime(date('Y-m-d H:i:s'));
   $interval = $datetime->diff($datetime_actual);
   return $interval->format('%R%a días');
 }

 function validaRut($rut){

  if(strpos($rut,"-") == false){
    # Cuerpo rut
    $RUT[0] = substr($rut, 0, -1);
    # Dígito verificador
    $RUT[1] = substr($rut, -1);
  }
  else{
    $RUT = explode("-", trim($rut));
  }
  # Busca en el cuerpo del rut si existe '.' y lo reemplaza por vacío
  $elRut = str_replace(".", "", trim($RUT[0]));
  $factor = 2;
  $suma = 0;
  for($i = strlen($elRut)-1; $i >= 0; $i--):
      $factor = $factor > 7 ? 2 : $factor;
      $suma += $elRut{$i} * $factor++;
  endfor;

  $resto = $suma % 11;
  $dv = 11 - $resto;
  if($dv == 11) {
      $dv=0;
  }
  else if($dv == 10){
      $dv="k";
  }
  else {
      $dv=$dv;
  }
  if($dv == trim(strtolower($RUT[1]))) {
     return true;
  }
  else {
     return false;
  }
}

?>
