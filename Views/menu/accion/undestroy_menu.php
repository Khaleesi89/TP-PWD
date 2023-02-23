<?php
require_once('../../../config.php');
$objMenuCon = new MenuController();
$data = data_submitted();//[idmenu]
$rta = $objMenuCon->buscar($data);
if($rta){
    $si = $rta->Noeliminar();
    if($si['respuesta']){
        $mensaje = "Se habilitó nuevamente el menú";
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
