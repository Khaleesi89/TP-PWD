<?php
require_once('../../../config.php');
$objMenuCon = new MenuController();
$data = data_submitted();//[menombre][medescripcion] [idpadre]
$rta = $objMenuCon->insertar($data);
if($rta['respuesta']){
    $respuesta = true;
    $mensaje = "Nuevo Menú exitoso";
}else{
    $respuesta = false;
    $mensaje = "La accion no pudo completarse";
}
$retorno['respuesta'] = $respuesta;
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);