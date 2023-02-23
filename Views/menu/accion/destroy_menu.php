<?php
require_once('../../../config.php');
$objMenuCon = new MenuController();
$data = data_submitted();//[idmenu]
$rta = $objMenuCon->buscar($data);
if($rta){
    $si = $rta->eliminar();
    if($si['respuesta']){
        $mensaje = "Se eliminó el menú";
        $retorno['respuesta'] = true;
    }else{
        $mensaje = "La acción no pudo concretarse";
        $retorno['respuesta'] = false;
    }
}else{
    $mensaje = "La acción no pudo concretarse";
    $retorno['respuesta'] = false;
}
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);