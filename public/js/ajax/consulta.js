var XMLHttp = crearInstancia();

function newConsulta(usuario) {

  if (XMLHttp) {
    tiempoSolucion = document.getElementById('tiempo_solucion').value;
    solicitud = document.getElementById('solicitud').value;
    btnEnviar = document.getElementById('enviar');
    problemaOportunidad = document.getElementById('problema_oportunidad').value;
    metProdServ = document.getElementById('met_prod_serv').value;
    productosObtener = document.getElementById('productos_obtener').value;
    procesoProductivo = document.getElementById('proceso_productivo').value;

    if (!isEmpty(solicitud) && !isEmpty(tiempoSolucion) && !isEmpty(problemaOportunidad) && !isEmpty(metProdServ) && !isEmpty(productosObtener) && !isEmpty(procesoProductivo)) {
      param = 'solicitud='+solicitud+'&usuario='+usuario+"&tiempoSolucion="+tiempoSolucion+"&problemaOportunidad="+problemaOportunidad+"&metProdServ="+metProdServ+"&productosObtener="+productosObtener+"&procesoProductivo="+procesoProductivo;
      XMLHttp.onreadystatechange = stateNewConsulta;
      XMLHttp.open('POST', 'controllers/controller.consulta.php?action=new_consulta', true);
      XMLHttp.send(param);
    }
    else {
      setTimeout(function() {
          toastr.options = {
              closeButton: true,
              progressBar: true,
              showMethod: 'slideDown',
              timeOut: 4000
          };
          toastr.warning('Debe completar todos los campos antes de enviar la consulta.', 'Un momento.');
      }, 1300);
    }
  }
}
function stateNewConsulta() {
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    XMLHttp.setRequestHeader('charset', 'utf-8');

    btnEnviar.disabled = true;
    btnEnviar.innerHTML = '<i class="fa fa-spinner fa-pulse fa-fw"></i> Consultar';
  }
  else {
    if (XMLHttp.readyState == 4) {
      disabled = true;
      switch (XMLHttp.responseText) {
        case '1':
          toastr.success('Consulta enviada correctamente.', 'Muy bien.');
          setTimeout(function() {
            location.href = 'index.php?m=1&a=2';
          }, 4000);
          break;
        case '2':
          toastr.error('No se ha podido enviar la consulta.', 'Lo sentimos.');
          disabled = false;
          break;
        case '3':
          toastr.error('Ha ocurrido un error al procesar el formulario, inténtelo nuevamente.', '¡Alto ahí!');
          disabled = false;
          break;
        case '4':
          toastr.warning('Debe completar todos los campos para enviar una consulta.', 'Lo sentimos.');
          disabled = false;
          break;
        case '5':
          toastr.error('Ha ocurrido un error inesperado. inténtelo más tarde.', '¡Ups!');
          disabled = false;
          break;
        default:
          toastr.error('Ha ocurrido un error inesperado. inténtelo más tarde.', '¡Ups!');
          disabled = false;
          break;
      }
      btnEnviar.disabled = disabled;
      btnEnviar.innerHTML = '<i class="fa fa-reply"></i> Consultar'
      console.log(XMLHttp.responseText);
    }
  }
}

function responderConsulta(consulta, usuario, email) {
  XMLHttp = crearInstancia();
  if (XMLHttp) {

    respuesta = document.getElementById('mensaje').value;
    verificar = 0;

    if ($("#enRevision").is(":checked"))
      verificar = 0;
    else if ($("#aceptar").is(":checked"))
      verificar = 1;
    else if ($("#rechazar").is(":checked"))
      verificar = 2;

    if (!isEmpty(consulta) && !isEmpty(usuario) && !isEmpty(respuesta) && !isEmpty(email)) {
      param = "consulta="+consulta+"&usuario="+usuario+"&respuesta="+respuesta+"&email="+email+"&verificar="+verificar;
      XMLHttp.onreadystatechange = stateResponderConsulta;
      XMLHttp.open("POST", "controllers/controller.consulta.respuesta.php?action=responder_consulta", true);
      XMLHttp.send(param);
      console.log(param);
    }
    else {
      toastr.warning('Debe completar de forma correcta y responder.', 'Lo sentimos.');
    }
  }
}
function stateResponderConsulta() {

  disabled = true;

  if (XMLHttp.readyState == 1) {
    document.getElementById('responder').disabled = disabled;
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    XMLHttp.setRequestHeader('charset', 'utf-8');
  }
  else {
    if (XMLHttp.readyState == 4) {

      responseText = XMLHttp.responseText;
      op = responseText.split("|");

      switch (op[0]) {
        case '1':
          $(".cont-respuestas").append(op[1]);
          $(".mail-footer").remove();
          break;
        case '2':
          disabled = false;
          toastr.error('No se ha podido responder la consulta. inténtelo más tarde', 'Lo sentimos.');
          break;
        case '3':
          disabled = false;
          toastr.error('Ha ocurrido un error al procesar la respuesta, inténtelo nuevamente.', '¡Alto ahí!');
          break;
        case '4':
          disabled = false;
          toastr.warning('Debe completar de forma correcta y responder.', 'Lo sentimos.');
          break;
        case '5':
          disabled = false;
          toastr.error('Ha ocurrido un error inesperado. inténtelo más tarde.', '¡Ups!');
          break;
        default:
          disabled = false;
          toastr.error('Ha ocurrido un error inesperado. inténtelo más tarde.', '¡Ups!');
          break;
      }

      if (!disabled)
        document.getElementById('responder').disabled = disabled;

      console.log(XMLHttp.responseText);
    }
  }
}

function deleteConsulta(consulta) {
  XMLHttp = crearInstancia();
  if (XMLHttp) {
    if (!isEmpty(consulta)) {
      XMLHttp.onreadystatechange = stateDeleteConsulta;
      XMLHttp.open("POST", "controllers/controller.consulta.php?delete_consulta", true);
      XMLHttp.send("consulta="+consulta);
    }
  }
}
function stateDeleteConsulta() {
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    XMLHttp.setRequestHeader('charset', 'utf-8');
  }
  else {
    if (XMLHttp.readyState == 4) {

    }
  }
}

function attachmentFile(consulta, usuarioConsulta) {
  if (confirm("Si ya existe un documento asociado a la consulta será eliminado. ¿Desea continuar?")) {
    XMLHttp = crearInstancia();
    if (XMLHttp) {
      if (!isEmpty(consulta)) {

        file = document.getElementById('attachment_file');
        form = document.getElementById('form_attachment_file');
        formData = new FormData(form);
        formData.append('consulta', consulta);
        formData.append('usuarioConsulta', usuarioConsulta);

        XMLHttp.onreadystatechange = stateAttachmentFile;
        XMLHttp.open("POST", "controllers/controller.consulta.php?action=attachment_file", true);
        XMLHttp.send(formData);
      }
      else {
        toastr.danger('Asegúrece de que esta todo correcto', '¡Un momento!');
      }
    }
    else {
      alert('Navegador Incompatible');
    }
  }
}
function stateAttachmentFile() {
  if (XMLHttp.readyState == 1) {
    file.disabled = true;
  }
  else {
    if (XMLHttp.readyState == 4) {

      responseText = XMLHttp.responseText;
      op = responseText.split("|");
      switch (op[0]) {
        case '1':
          toastr.success('Se ha subido correctamente el documento.', '¡Muy Bien!');
          document.getElementById('documento-consulta').innerHTML = op[1];
          $("html, body").animate({ scrollTop: 0 }, "slow");
          break;
        case '2':
          toastr.error('No se pudo guardar el documento correctamente. Pongase en contacto con el administrador del sitio web.', 'Lo sentimos');
          break;
        case '3':
          toastr.error('No se ha podido subir el documento. Asegurece de que el documento no sobrepase el tamaño máximo (20MB).', 'Lo sentimos');
          break;
        case '4':
          toastr.error('Ha ocurrido un problema de relación con la consulta.', 'Lo sentimos');
          setTimeout(function () {
            location.reload();
          }, 4000);
        case '5':
          toastr.error('Ha ocurrido un problema de relación con la consulta.', 'Lo sentimos');
          setTimeout(function () {
            location.reload();
          }, 4000);
          break;
        case '6':
          toastr.error('No se ha podido subir el documento. inténtelo nuevamante', 'Lo sentimos');
          break;
        case '7':
          toastr.error('Ha ocurrido un problema al procesar el documento.', 'Lo sentimos');
          break;
        default:
          break;
      }
      file.disabled = false;
      // console.log(XMLHttp.responseText);
    }
  }
}


function attachmentEmail() {
  XMLHttp = crearInstancia();
  if (XMLHttp) {

    email = document.getElementById('email_especialista').value;
    requerimiento = document.getElementById('hidden_requerimiento').value;
    folio = document.getElementById('hidden_folio').value;

    if (!isEmpty(email) && !isEmpty(requerimiento) && !isEmpty(folio)) {
      param = "email="+email+"&requerimiento="+requerimiento+"&folio="+folio;
      XMLHttp.onreadystatechange = stateAttachmentEmail;
      XMLHttp.open("POST", "controllers/controller.consulta.php?action=attachment_email", true);
      XMLHttp.send(param);
    }
  }
}
function stateAttachmentEmail() {
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  }
  else {
    if (XMLHttp.readyState == 4) {

      switch (XMLHttp.responseText) {
        case '1':
          toastr.success('Se ha enviado correctamente el requerimiento al especialista.', 'Muy Bien.');
          break;
        case '2':
          toastr.error('No se pudo enviar el requerimiento. Asegúrese de haber escrito correctamente el o los correos electrónicos e inténtelo nuevamente.', 'Lo sentimos.');
          break;
        case '3':
          toastr.warning('Debe ingresar un correo electrónico antes de enviar.', 'Un momento.');
          break;
        case '4':
          toastr.warning('Debe completar de manera correcta antes de enviar.', 'Un momento.');
          break;
        default:
          toastr.warning('Ha ocurrido un error, inténtelo nuevamente.', 'Un momento.');
      }
    }
  }
}
function buscarRequerimiento() {
  XMLHttp = crearInstancia();
  if (XMLHttp) {
    search = document.getElementById('search').value;
    XMLHttp.onreadystatechange = stateBuscarRequerimiento;
    XMLHttp.open("POST", "controllers/controller.consulta.php?action=buscar_requerimiento", true);
    XMLHttp.send("search="+search);
  }
}
function stateBuscarRequerimiento() {
  if (XMLHttp.readyState == 1) {
    XMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    document.getElementById('feed-requerimientos').innerHTML = '<div class="feed-element text-center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>';
  }
  else {
    if (XMLHttp.readyState == 4) {
      document.getElementById('feed-requerimientos').innerHTML = XMLHttp.responseText;
    }
    // console.log(XMLHttp.responseText);
  }
}
