<?php
require_once('../../../config.php');
$objRolCon = new RolController();
$data = data_submitted();//['rodescripcion']
$rta = $objRolCon->insertar($data);
if($rta){
    $respuesta = true;
    $mensaje = "Insertó un nuevo rol";
}else{
    $respuesta = false;
    $mensaje = "La accion no pudo completarse";
}
$retorno['respuesta'] = $respuesta;
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);