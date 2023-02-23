<?php
require_once('../../../config.php');
$objUsuCon = new UsuarioController();
$obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
$obtenerURL = array_reverse($obtenerURL);
$url = explode("=",$obtenerURL[0]);
$idpersona = $url[1];
$editado = data_submitted();//TRAE TDOS LOS CAMBIOS MENOS EL ID
$editado['idusuario'] = $idpersona;
$rta = $objUsuCon->modificar($editado);
if($rta){
    $mensaje = "se editó al usuario correctamente";
    $retorno['respuesta'] = true;
}else{
    $mensaje = "La acción no pudo concretarse";
    $retorno['respuesta'] = false;
}

if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);

