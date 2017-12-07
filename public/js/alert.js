// Muestra un mensaje de alerta flotante | Tipo de alerta => Advertencia
function alertForm(strong, msj, tipo_alert) {
  alert = '<div style="display:none;" class="alert '+tipo_alert+' alert-dismissible alert-floating" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>'+strong+'</strong> '+msj+'</div>';
  $(alert).appendTo('body').slideDown(300);
}
