<?php
require_once('../../../config.php');
$objConCompraItem = new CompraitemController();
$data = data_submitted();//[idcompraitem]
$rta = $objConCompraItem->buscar($data);
if($rta){
    $salida = $rta->eliminar();
    if($salida['respuesta']){
        $mensaje = "Eliminación exitosa";
        $finish = true;
    }else{
        $mensaje = "La acción no pudo concretarse";
        $finish = false;
    }
}else{
    $mensaje = "La acción no pudo concretarse";
    $finish = false;
}
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);