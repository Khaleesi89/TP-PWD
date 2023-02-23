<?php
require_once('../../../config.php');
$objRolCon = new RolController();
$data = data_submitted();
$rta = $objRolCon->buscar($data);
if($rta){
    //entra si hay algo
    $respusta = $rta->eliminar();
    if($respusta['respuesta']){
        $retorno['respuesta'] = true;
        $mensaje = "Se eliminó el rol";
    }else{
        $retorno['respuesta'] = false;
        $mensaje = "No se eliminó el rol";
    }
}else{
    //si no lo encuentra
    $retorno['respuesta'] = false;
    $mensaje = "No existe el rol";
}
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);