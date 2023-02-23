<?php
require_once('../../../config.php');
$objCompraCont = new CompraController();
$data = data_submitted();//[idcompra] =>

$result = $objCompraCont->buscar($data);
if($result){
    $rta = $objCompraCont->eliminar($data);
    if($rta){
        $retorno['respuesta'] = true;
        $mensaje = "Su compra ha sido eliminada correctamente";
    }else{
        $retorno['respuesta'] = false;
        $mensaje = "La acción no pudo concretarse";
    }
}

if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);