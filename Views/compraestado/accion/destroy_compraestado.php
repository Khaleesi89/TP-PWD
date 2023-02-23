<?php
require_once('../../../config.php');
$objCompraEstadoController = new CompraestadoController();
$data = data_submitted();//[idcompraestado] 
$objBuscado = $objCompraEstadoController->buscar($data);
if($objBuscado){
    $objBuscado->eliminar();
    $retorno['respuesta'] = true;
}else{
    $retorno['respuesta'] = false;
}

$respuesta = false;
if($data != null){
   $rta = $objCompraEstadoController->eliminar();
   if( !$rta ){
    $mensaje = "La acción no pudo concretarse";
   }
}

$retorno['respuesta'] = true;
if( isset($mensaje) ){
    $retorno['errorMsg'] = $mensaje;
}

echo json_encode($retorno);