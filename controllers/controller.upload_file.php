<?php
// if (isset($_POST["name2"])) {
//   echo subir_archivo($_FILES["name1"]["name"], $_FILES["name1"]["tmp_name"], $_FILES["name1"]["type"], $_FILES["name1"]["size"],'','');
// }
/**
  *Libreria para subir documentos al servidor
  *
  *@author Pablo Gatica López
  *@copyright 24-09-2016
  *@since 1.3
  *
  *Extención | Tipo de Archivo                                | MIME Type
  *---------------------------Microsoft Office---------------------------------*
  *.doc      | Microsoft Office Word 2003                     | application/msword
  *.xls      | Microsoft Office Excel 2003                    | application/vnd.ms-excel
  *.ppt      | Microsoft Office PowerPoint 2003               | application/vnd.ms-powerpoint
  *.docx     | Microsoft Office Word 2007 document            | application/vnd.openxmlformats-officedocument.wordprocessingml.document
  *.xlsx     | Microsoft Office Excel 2007 workbook           | application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
  *.pptx     | Microsoft Office PowerPoint 2007 presentation  | application/vnd.openxmlformats-officedocument.presentationml.presentation
  *---------------------------Otros--------------------------------------------*
  *.pdf      | Microsoft Office Word 2007 document            | application/pdf
  *.txt      | Archivo de Texto                               | text/plain
  *.jpge     | Imagen JPG JPGE                                | image/jpge
  *.png      | Imagen PNG                                     | image/png
  *.gif      | Imagen GIF                                     | image/gif
*/

/* ---------------------------------------------- */
// Bloque para subir un archivo al servidor
/* ---------------------------------------------- */

function subir_archivo($nombre, $nombre_tmp, $tipo, $size, $destino, $prefijo)  {
  $new_nombre = "";
  // Arreglo que contiene los MIME Type soportados por esta libreria
  $soportado = array(
    "application/msword",
    "application/vnd.ms-excel",
    "application/vnd.ms-powerpoint",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    "application/vnd.openxmlformats-officedocument.presentationml.presentation",
    "application/pdf",
    "text/plain",
    "image/jpge",
    "image/png",
    "image/gif"
  );
  // Preguntamos si en el array se encuentra MIME Type que estamos subiendo
  if (in_array($tipo,$soportado)) {
    // Esta condición indica que solo se pueden subir archivos con un tamaño menor o igual a 20MB
    if ($size <= (20*(1024*1024))) {
      // Si entra al if es por que cumple con el tamaño permitido
      if(copy($nombre_tmp, $destino.$prefijo.$nombre)) {
        // Entra al if si se copio exitosamente el archivo
        $new_nombre = $prefijo.$nombre;
      }
    }
  }
  return $new_nombre;
}

/* ---------------------------------------------- */
// Bloque para obtener la extensión de un archivo
/* ---------------------------------------------- */

function get_extension($mime) {

  /*
             |        0           |            1             | 2...
           --|--------------------|--------------------------|-------
           0 | application/msword | application/vnd.ms-excel | etc...
           --|--------------------|--------------------------|-------
           1 | .doc               | .xls                     | etc...
           --|--------------------|--------------------------|-------
  */

  // Arreglo bidimensional que contiene los MIME Type soportados por esta libreria y Las extensiones
  $soportado = array(
    array(
      "application/msword",
      "application/vnd.ms-excel",
      "application/vnd.ms-powerpoint",
      "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
      "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
      "application/vnd.openxmlformats-officedocument.presentationml.presentation",
      "application/pdf","text/plain",
      "image/jpeg",
      "image/png",
      "image/gif"
    ),
    array(
      ".doc",
      ".xls",
      ".ppt",
      ".docx",
      ".xlsx",
      ".pptx",
      ".pdf",
      ".txt",
      ".jpg",
      ".png",
      ".gif"
    )
  );
  for ($i = 0; $i < count($soportado); $i++) {
    for ($j = 0; $j < count($soportado[$i]); $j++) {
      /*Si el MIME Type que estamos pasando por parametros esta en alguna columna de la fila 0 del arreglo (Donde se encunetran las Mimes)
      Se guarda la extension que esta en la fila 1 (Donde se encuentran las extensiones) y la columna que indica $j*/
      if ($mime == $soportado[0][$j]) {
        $extension = $soportado[1][$j];
      }
    }
  }
  return $extension;
}


?>
