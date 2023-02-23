<?php
require_once('../../../config.php');
$objConPro = new ProductoController();
$data = data_submitted();//[pronombre] [sinopsis] [procantstock] =[autor] [precio][isbn][categoria]
$obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
$obtenerURL = array_reverse($obtenerURL);
$url = explode("=",$obtenerURL[0]);
$idproducto = $url[1];
$data['idproducto'] = $idproducto;
$rta = $objConPro->modificar($data);
if($rta){
    $retorno['respuesta'] = true;
    $mensaje = "Modificación exitosa";
}else{
    $retorno['respuesta'] = false;
    $mensaje = "La accion no pudo concretarse";
}
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);