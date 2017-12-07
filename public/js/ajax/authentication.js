function registrarme() {

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

    if (!isEmpty(nombre) && !isEmpty(apellido) && !isEmpty(emailContacto) && !isEmpty(razonSocial) &&
    !isEmpty(rut) && !isEmpty(direccion) && !isEmpty(comuna) && !isEmpty(celular)) {

      param = "nombre_contacto="+nombre+"&apellido_contacto="+apellido+"&email_contacto="+emailContacto+"&razon_social="+razonSocial+"&rut="+rut+"&act_productiva="+actProductiva+"&direccion="+direccion+"&comuna="+comuna+"&telefono="+telefono+"&celular="+celular+"&email_empresa="+emailEmpresa+"&email_opcional="+emailOpcional;
      XMLHttp.onreadystatechange = stateRegistrarme;
      XMLHttp.open("POST", "controllers/controller.ficha.php?action=registrarme", true);
      XMLHttp.send(param);

    }
    else {
      toastr.warning("Debe completar todos los campos para registrarse.", "Un momento.");
    }
  }
  else {
    alert("Ha ocurrido un error.");
  }

}
function stateRegistrarme() {
  disabled = true;
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    document.getElementById('enviar').disabled = disabled;
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
      document.getElementById('enviar').disabled = disabled;
      if (disabled)
        setTimeout(function() {
          location.reload();
        }, 8000);
      console.log(XMLHttp.responseText);
    }
  }
}

function crearPassword(email, token, expdate) {

  XMLHttp = crearInstancia();

  if (XMLHttp) {

    urlEmail = email;
    password = $("#password").val();
    passwordRepeat = $("#password_repeat").val();
    btnPasswordCreate = document.getElementById('password_create');

    if (!isEmpty(password) && !isEmpty(passwordRepeat) && !isEmpty(email) && !isEmpty(token) && !isEmpty(expdate)) {
      if (password == passwordRepeat) {
        param = "password="+password+"&passwordRepeat="+passwordRepeat+"&email="+email+"&token="+token+"&expdate="+expdate;
        XMLHttp.onreadystatechange = stateCrearPassword;
        XMLHttp.open("POST", "controllers/controller.password.create.php?action=password_create", true);
        XMLHttp.send(param);
      }
      else {
        toastr.warning("Las contraseñas son distintas, intentelo nuevamente.", "Un momento");
        $("#password").val("");
        $("#password_repeat").val("");
        $("#password").focus();
      }
    }
    else {
      toastr.warning("Debe completar todos los campos antes de continuar.", "Un momento.");
    }
  }
  else {
    alert("Navegador incompatible");
  }
}
function stateCrearPassword() {
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    XMLHttp.setRequestHeader('charset', 'utf-8');
    btnPasswordCreate.disabled = true;
    btnPasswordCreate.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i>';
  }
  else {
    if (XMLHttp.readyState == 4) {
      sw = false;
      switch (XMLHttp.responseText) {

        case '1':
          toastr.success("La contraseña se ha creado correctamente, lo estamos redirigiendo a su página principal.", "¡Muy Bien!");
          sw = true;
          btnPasswordCreate.disabled = sw;
          setTimeout(function() {
            location.href = "login.php?email="+urlEmail;
          }, 8000);
          break;

        case '2':
          toastr.error("No se pudo cambiar la contraseña, intentelo nuevamente", "Lo sentimos.");
          break;

        case '3':
          toastr.error("El token ha caducado o es inválido.", "Un momento.");
          break;

        case '4':
          toastr.warning("No coinciden las contraseñas, intentelo nuevamente", "Un momento.");
          break;

        case '5':
          toastr.error("Ha ocurrido un error al cambiar la contraseña, intentelo nuevamente.", "Lo sentimos.");
          break;

        case '6':
          toastr.error("Ha ocurrido un error al procesar el formulario.", "Lo sentimos.");
          break;

        default:
          toastr.error("Ha ocurrido un error al procesar el formulario.", "Lo sentimos.");
          break;
      }

      if (!sw) {
        setTimeout(function() {
          location.reload();
        }, 8000);
      }

      btnPasswordCreate.disabled = sw;
      btnPasswordCreate.innerHTML = 'CREAR';
      // console.log(XMLHttp.responseText);
    }
  }
}

function passwordForget() {

  XMLHttp = crearInstancia();
  if (XMLHttp) {

    email = $("#email").val();
    btnPasswordForget = document.getElementById('password_forget');

    if (!isEmpty(email)) {
      XMLHttp.onreadystatechange = statePasswordForget;
      XMLHttp.open("POST", "controllers/controller.password.create.php?action=password_forget", true);
      XMLHttp.send("email="+email);
    }
    else {
      toastr.warning("Debe completar el campo antes de solicitar un cambio de contraseña.", "Un momento.");
    }
  }
  else {
    alert("Navegador incompatible.");
  }
}
function statePasswordForget() {
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    btnPasswordForget.disabled = true;
    btnPasswordForget.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i>';
  }
  else {
    if (XMLHttp.readyState == 4) {

      disabled = false;

      switch (XMLHttp.responseText) {
        case '1':
          toastr.success("Se ha enviado una solicitud para recuperar su contraseña.", "¡Muy Bien!");
          disabled = true;
          setTimeout(function() {
            location.href = 'login.php?r=success_send&email='+email;
          }, 4000);
          break;
        case '2':
          toastr.error("No se ha podido enviar una solicitud para recuperar su contraseña. Intentelo nuevamente.", "Lo sentimos");
          setTimeout(function() {
            location.reload();
          }, 4000);
          break;
        case '3':
          toastr.error("Asegurece de haber escrito correctamente su correo asociado a la ventanilla virtual.", "Lo sentimos.");
          break;
        case '4':
          toastr.warning("El correo que ha escrito no esta registrado en la ventanilla virtual.", "Lo sentimos.");
          break;
        case '5':
          toastr.error("Ha ocurrido un error al procesar su solicitud, intentelo nuevamente.", "Lo sentimos.");
          break;
        case '6':
          toastr.error("Ha ocurrido un error al procesar su solicitud, intentelo nuevamente.", "Lo sentimos.");
          break;
        default:
      }

      btnPasswordForget.disabled = disabled;
      btnPasswordForget.innerHTML = 'Enviar Solicitud';
      console.log(XMLHttp.responseText);
    }
  }
}
