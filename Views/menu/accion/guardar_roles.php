<?php
require_once('../../../config.php');
$objMenuCon = new MenuController();
$objMenuRol = new MenuRolController();
//$retorno['respuesta'] = false;
$obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
$obtenerURL = array_reverse($obtenerURL);
$url = explode("=",$obtenerURL[0]);
$idmenu = $url[1];
$datas = data_submitted();// ej [rol2] => on [idmenu] => 
$datas['idmenu'] = $idmenu;
$resulta = $objMenuRol->intermediario($datas);
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

