function newUsuario() {
  XMLHttp = crearInstancia();
  if (XMLHttp) {
    password = $("#password_contacto").val();
    nombre = $("#nombre_contacto").val();
    apellido = $("#apellido_contacto").val();
    emailContacto = $("#email_contacto").val();
    emailOpcional = $("#email_opcional").val();
    razonSocial = $("#razon_social").val();
    rut = $("#rut").val();
    actProductiva = $("#act_productiva").val();
    direccion = $("#direccion").val();
    comuna = $("#comuna").val();
    telefono = $("#telefono").val();
    celular = $("#celular").val();
    emailEmpresa = $("#email_empresa").val();

    if (!isEmpty(password) && !isEmpty(nombre) && !isEmpty(apellido) && !isEmpty(emailContacto) && !isEmpty(razonSocial) &&
    !isEmpty(rut) && !isEmpty(direccion) && !isEmpty(comuna) && !isEmpty(celular)) {

      param = "password_contacto="+password+"&nombre_contacto="+nombre+"&apellido_contacto="+apellido+"&email_contacto="+emailContacto+"&razon_social="+razonSocial+"&rut="+rut+"&act_productiva="+actProductiva+"&direccion="+direccion+"&comuna="+comuna+"&telefono="+telefono+"&celular="+celular+"&email_empresa="+emailEmpresa+"&email_opcional="+emailOpcional;
      XMLHttp.onreadystatechange = stateNewUsuario;
      XMLHttp.open("POST", "controllers/controller.ficha.php?action=registrar_usuario", true);
      XMLHttp.send(param);

    }
    else {
      toastr.warning("Debe completar todos los campos para registrarse.", "Un momento.");
    }
  }
}
function stateNewUsuario() {
  disabled = true;
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    document.getElementById('registrar').disabled = disabled;
  }
  else {
    if (XMLHttp.readyState == 4) {
      switch (XMLHttp.responseText) {
        case 'empty':
          toastr.warning("Debe completar todos los campos para continuar con la suscripción.", "Lo sentimos.");
          disabled = false;
          break;
        case 'isset':
          toastr.error("Ha ocurrido un error inesperado. Intentelo más tarde.", "¡Ups!");
          disabled = false;
          break;
        case 'filter':
          toastr.error("Ha ocurrido un error al procesar el formulario, intentelo nuevamente.", "¡Alto ahí!");
          disabled = false;
          break;
        case 'exists_email':
          toastr.warning("El email o el RUT que esta intentado registrar ya existe.", "Lo sentimos.");
          disabled = false;
          break;
        case 'no_user':
          toastr.warning("No se ha podido ingresar al usuario.", "Lo sentimos.");
          disabled = false;
          break;
        case 'no_ficha':
          toastr.error("No se ha podido procesar la ficha de suscripción.", "Lo sentimos.");
          disabled = false;
          break;
        case 'success':
          toastr.success("Se ha enviado un correo electrónico al usuario para crear la clave secreta. Revisar bandeja de entrada, correo no deseado o SPAM.", "Muy bien.");
          disabled = true;
          break;
        case 'rut_invalido':
          toastr.warning("Asegurece de que el RUT sea válido e intentelo nuevamente.", "Un momento.");
          disabled = false;
          break;
        case 'no_mail':
          toastr.warning("Ha ocurrido un problema al tratar de enviar el correo electrónico", "Lo sentimos.");
          disabled = false;
          break;
        default:
          toastr.warning("Ha ocurrido un error al tratar de registrar su solicitud. Intentelo más tarde.", "Lo sentimos.");
          disabled = false;
          break;
      }
      document.getElementById('registrar').disabled = disabled;
      if (disabled)
        setTimeout(function() {
          location.reload();
        }, 8000);
      console.log(XMLHttp.responseText);
    }
  }
}

function updateUsuario(usuario) {
  XMLHttp = crearInstancia();
  if (XMLHttp) {

    nombre = $("#nombre_contacto").val();
    apellido = $("#apellido_contacto").val();
    emailContacto = $("#email_contacto").val();
    emailOpcional = $("#email_opcional").val();
    razonSocial = $("#razon_social").val();
    rut = $("#rut").val();
    actProductiva = $("#act_productiva").val();
    direccion = $("#direccion").val();
    comuna = $("#comuna").val();
    telefono = $("#telefono").val();
    celular = $("#celular").val();
    emailEmpresa = $("#email_empresa").val();

    if (!isEmpty(usuario) && !isEmpty(nombre) && !isEmpty(apellido) && !isEmpty(emailContacto) && !isEmpty(razonSocial) &&
    !isEmpty(rut) && !isEmpty(direccion) && !isEmpty(comuna) && !isEmpty(celular)) {

      param = "usuario="+usuario+"&nombre_contacto="+nombre+"&apellido_contacto="+apellido+"&email_contacto="+emailContacto+"&razon_social="+razonSocial+"&rut="+rut+"&act_productiva="+actProductiva+"&direccion="+direccion+"&comuna="+comuna+"&telefono="+telefono+"&celular="+celular+"&email_empresa="+emailEmpresa+"&email_opcional="+emailOpcional;
      XMLHttp.onreadystatechange = stateUpdateUsuario;
      XMLHttp.open("POST", "controllers/controller.ficha.php?action=modificar_usuario", true);
      XMLHttp.send(param);

    }
    else {
      toastr.warning("Debe completar todos los campos para modificar.", "Un momento.");
    }
  }
}
function stateUpdateUsuario() {
  disabled = true;
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    document.getElementById('modificar_usuario').disabled = disabled;
  }
  else {
    if (XMLHttp.readyState == 4) {
      switch (XMLHttp.responseText) {
        case 'empty':
          toastr.warning("Debe completar todos los campos para continuar con la suscripción.", "Lo sentimos.");
          disabled = false;
          break;
        case 'isset':
          toastr.error("Ha ocurrido un error inesperado. Intentelo más tarde.", "¡Ups!");
          disabled = false;
          break;
        case 'filter':
          toastr.error("Ha ocurrido un error al procesar el formulario, intentelo nuevamente.", "¡Alto ahí!");
          disabled = false;
          break;
        case 'exists_email':
          toastr.warning("El email o el RUT que esta intentado modificar ya existe.", "Lo sentimos.");
          disabled = false;
          break;
        case 'no_user':
          toastr.warning("No se ha podido modificar al usuario.", "Lo sentimos.");
          disabled = false;
          break;
        case 'no_ficha':
          toastr.error("No se ha podido procesar la ficha de suscripción.", "Lo sentimos.");
          disabled = false;
          break;
        case 'success':
          toastr.success("Se ha enviado un correo electrónico al usuario para notificar el cambio.", "Muy bien.");
          disabled = true;
          break;
        case 'rut_invalido':
          toastr.warning("Asegurece de que el RUT sea válido e intentelo nuevamente.", "Un momento.");
          disabled = false;
          break;
        case 'no_mail':
          toastr.warning("Ha ocurrido un problema al tratar de enviar el correo electrónico", "Lo sentimos.");
          disabled = false;
          break;
        default:
          toastr.warning("Ha ocurrido un error al tratar de modificar su solicitud. Intentelo más tarde.", "Lo sentimos.");
          disabled = false;
          break;
      }
      document.getElementById('modificar_usuario').disabled = disabled;
      if (disabled)
        setTimeout(function() {
          location.href = 'index.php?m=3&a=13';
        }, 8000);
      // console.log(XMLHttp.responseText);
    }
  }
}

function deleteUser(usuario) {
  XMLHttp = crearInstancia();
  if (confirm("¿Desea eliminar el usuario?")) {
    XMLHttp.onreadystatechange = stateDeleteUsuario;
    XMLHttp.open("POST", "controllers/controller.ficha.php?action=delete_usuario", true);
    XMLHttp.send("usuario="+usuario);
  }
}
function stateDeleteUsuario() {
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  }
  else {
    if (XMLHttp.readyState == 4) {
      response = XMLHttp.responseText.split('|');
      switch (response[0]) {
        case '1':
          document.getElementById('table_usuarios').innerHTML = response[1];
          break;
        default:
          toastr.warning("No se ha podido eliminar el usuario.", "Lo sentimos");
          break;
      }
    }
  }
}

function buscarUsuario() {
  XMLHttp = crearInstancia();
  if (XMLHttp) {
    rut = document.getElementById('search').value;
    XMLHttp.onreadystatechange = stateBuscarUsuario;
    XMLHttp.open("POST", "controllers/controller.ficha.php?action=buscar_usuario", true);
    XMLHttp.send("rut="+rut);
  }
}
function stateBuscarUsuario() {
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    document.getElementById('table_usuarios').innerHTML = '<tr><td colspan="5" align="center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></td></tr>';
  }
  else {
    if (XMLHttp.readyState == 4) {
      response = XMLHttp.responseText.split("|");
      switch (response[0]) {
        case '1':
          document.getElementById('table_usuarios').innerHTML = response[1];
          break;
        case '2':
          document.getElementById('table_usuarios').innerHTML = '<tr><td colspan="5" align="center">No se ha encontrado el usuario.</td></tr>';
          break;
        default:
          document.getElementById('table_usuarios').innerHTML = '<tr><td colspan="5" align="center">No se ha encontrado el usuario.</td></tr>';
          break;
      }
    }
    // console.log(XMLHttp.responseText);
  }
}
