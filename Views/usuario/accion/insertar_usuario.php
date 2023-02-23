<?php
require_once('../../../config.php');
$objUsuCon = new UsuarioController();
$dato = data_submitted();
$data = $objUsuCon->insertar($dato);
if($data){
    $respuesta = true;
}else{
    $mensaje = "La accion no pudo completarse";
    $respuesta = false;
}
$retorno['respuesta'] = $respuesta;
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);