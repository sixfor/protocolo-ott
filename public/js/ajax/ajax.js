function crearInstancia(){

	XMLHttp = false;
	if (window.XMLHttpRequest)
	{
		return new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		var versiones = ["Msxm12.XMLHTTP.7.0", "Msxm12.XMLHTTP.6.0", "Msxm12.XMLHTTP.5.0",
						"Msxm12.XMLHTTP.4.0", "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP"];
		for (var i = 0; i < versiones.length; i++)
		{
			try{
				XMLHttp = new ActiveXObject(versiones[i]);
				if (XMLHttp) {
					return XMLHttp;
					break;
				}
			}
			catch (e) {};
		}
	}
}

function isEmpty(e) {
  if (e == undefined)
    return true;
  else if (e.length == 0)
    return true;
  else if (e == '')
    return true;
  else
    return false;
}
