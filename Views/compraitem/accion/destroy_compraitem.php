<?php
require_once('../../../config.php');
$objConCompraItem = new CompraitemController();
$data = data_submitted();//[idcompraitem]
$rta = $objConCompraItem->buscar($data);
if($rta){
    $salida = $rta->eliminar();
    if($salida['respuesta']){
        $finish = true;
        $mensaje = "Eliminación exitosa";
        
    }else{
        $finish = false;
        $mensaje = "La acción no pudo concretarse";
       
    }
}else{
    $finish = false;
    $mensaje = "La acción no pudo concretarse";
    
}
$retorno['respuesta'] = $finish;
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);