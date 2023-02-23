<?php
require_once('../../../config.php');

$objConPro = new ProductoController();
$data = data_submitted();//[pronombre] =>[sinopsis] =>[procantstock][autor][precio][isbn][categoria]
$rta = $objConPro->insertar($data);
if($rta['respuesta']){
    $respuesta = true;
    $mensaje = "Producto incorporado";
}else{
    $respuesta = false;
    $mensaje = "La accion no pudo completarse";
}
$retorno['respuesta'] = $respuesta;
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);