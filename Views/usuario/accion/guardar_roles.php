<?php
require_once('../../../config.php');
$objUsuarioRolCon = new UsuarioRolController();
$retorno['respuesta'] = false;
//borrar roles de usuario
$obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
$obtenerURL = array_reverse($obtenerURL);
$url = explode("=",$obtenerURL[0]);
$idusuario = $url[1];
$datosss = data_submitted();//[rol3] => on lo q esta marcado
$datosss['idusuario'] = $idusuario;
$resulta = $objUsuarioRolCon->intermediario($datosss);
if($resulta){
    $mensaje = "La transacción se realizó correctamente";
    $retorno['respuesta'] = true; 
}else{
    $mensaje = "No se realizó la transacción";
    $retorno['respuesta'] = false;
}
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);
