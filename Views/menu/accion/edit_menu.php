<?php
require_once('../../../config.php');
$objMenuCon = new MenuController();
$obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
$obtenerURL = array_reverse($obtenerURL);
$datos = explode("=",$obtenerURL[0]);
$idmenu = $datos[1];
$data = data_submitted();
$data['idmenu'] = $idmenu;
if($data != null){
    $rta = $objMenuCon->modificar($data);
    if(!$rta){
        $mensaje = "La accion no pudo concretarse";
    }
}
$retorno['respuesta'] = $rta;
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);