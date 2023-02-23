<?php
require_once('../../../config.php');
$objRolCon = new RolController();
$data = data_submitted();//[rodescripcion] 
$obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
$obtenerURL = array_reverse($obtenerURL);
$url = explode("=",$obtenerURL[0]);
$idrol = $url[1];
$roleando['idrol'] = $idrol;
$data['idrol'] = $idrol;
$rta = $objRolCon->buscar($roleando);
if($rta){
    $rta = $objRolCon->modificar($data);
    if($rta){
        $retorno['respuesta'] = true;
        $mensaje = "Pudo modificarse";
    }else{
        $retorno['respuesta'] = false;
        $mensaje = "No Pudo modificarse";
    }
}
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);