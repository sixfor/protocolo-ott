<?php
//Funcion para filtrar vaariables GET y POST

function limpiar($cadena)
{
	$caracteres = array("information_schema","union","select","'","delete","insert","update","drop","like","join","-- ","where","from","script","<script>","</script>","<noscript>");
	$cadena_limpia=str_replace($caracteres,"",strtolower($cadena));
	return $cadena_limpia;
}
function limpiar_mapa($cadena)
{
	$caracteres = array("information_schema","union","select","delete","insert","update","drop","like","join","where","from","script","<script>","</script>","<noscript>");
	$cadena_limpia=str_replace($caracteres,"",strtolower($cadena));
	return $cadena_limpia;
}
function limpiar_reg_user($cadena)
{
	$caracteres = array("select","'","delete","insert","update","drop","%","like","join","@","--",",","where","from","(",")","()","script","<script>","</script>","<noscript>");
	$cadena_limpia=str_replace($caracteres,"",$cadena);
	return $cadena_limpia;
}
function limpiar_mail($cadena)
{
	$caracteres = array("select","'","delete","insert","update","drop","%","--"," ",",","where","(",")","()","script","<script>","</script>","<noscript>");
	$cadena_limpia=str_replace($caracteres,"",strtolower($cadena));
	return $cadena_limpia;
}
function limpiar_clave($cadena)
{
	$caracteres = array("select","'","--","-","delete","insert","and","update","drop","%","like","join",",","@",".","where","from","(",")","()","+","script","<script>","</script>","<noscript>");
	$cadena_limpia=str_replace($caracteres,"",$cadena);
	return $cadena_limpia;
}
function limpiar_num($cadena)
{
	$caracteres = array("select","'","--","-","delete","insert","or","and","update","drop","%","like","join",".",",","where","from","(",")","()","+","*","/","script","<script>","</script>","<noscript>");
	$cadena_limpia=str_replace($caracteres,"",strtolower($cadena));
	return $cadena_limpia;
}
function limpiar_codigo($cadena)
{
	$caracteres = array("select","'"," --","-","delete","insert","update","drop","%","like","join",".","where","from","(",")","()","+","*","/","script","<script>","</script>","<noscript>");
	$cadena_limpia=str_replace($caracteres,"",$cadena);
	return $cadena_limpia;
}
function archivo($archivo)
{
	$tipo=NULL;
	$archivo=limpiar($archivo);
	$vector_file=explode("/",$archivo);
	$vector_file_count=count($vector_file);
	for($i=0;$i<$vector_file_count;$i++)
	{
		if($vector_file[$i]=="pdf" or $vector_file[$i]=="image")
		{
			$tipo=1;
		}
	}
	return $tipo;
}

/**
*
*Funcion insecto() busca si alguien accede
*de forma directa a un documento prohibido por url
*como parametro recibe la url y el archivo a buscar
*retorna true si el archivo esta en la url
*retorna false si el archivo no esta en la url
*/
function insecto($url,$file){
	$url = strtolower($url);
	$file = strtolower($file);
	if(strpos($url,$file)!==false){
		return true;
	}else{
		return false;
	}

}

function encriptar($dato,$clave){
	$algoritmo = "rijndael-128";
  $modo = MCRYPT_MODE_ECB;
  $iv = mcrypt_create_iv(mcrypt_get_iv_size($algoritmo, $modo),MCRYPT_RAND);
	$dato_cifrado = mcrypt_encrypt($algoritmo, $clave, $dato, $modo,$iv);
	$dato_cifrado_base64=base64_encode($dato_cifrado);
	$dato_cifrado_base64_urlencode=urlencode($dato_cifrado_base64);
	return $dato_cifrado_base64_urlencode;
}
function descifrar($dato,$clave){
	$algoritmo = "rijndael-128";
  $modo = MCRYPT_MODE_ECB;
  $iv = mcrypt_create_iv(mcrypt_get_iv_size($algoritmo, $modo),MCRYPT_RAND);
	//si configuro el code con la linea de abajo, hay problemas con el + en la url al descifrar
	//$dato_descifrado_urldecode=urldecode($dato);
	$dato_descifrado_urldecode_base64_decode=base64_decode($dato);
	//$dato_descifrado = substr($dato_descifrado_urldecode_base64_decode,mcrypt_get_iv_size($algoritmo, $modo));
	$dato_descifrado = mcrypt_decrypt($algoritmo, $clave, $dato_descifrado_urldecode_base64_decode, $modo,$iv);
	$dato_descifrado=rtrim($dato_descifrado);
	return $dato_descifrado;
}

?>
