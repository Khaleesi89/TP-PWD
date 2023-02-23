<?php

require_once('../../../config.php');
$objUsuCon = new UsuarioController();
$eliminado = data_submitted();//idusuario
$usuarioEliminado = $objUsuCon->buscarUsuario($eliminado);
$rta = $usuarioEliminado->Noeliminar();
if($rta){
    $mensaje = "se habilitó al usuario correctamente";
    $retorno['respuesta'] = true;
}else{
    $mensaje = "La acción no pudo concretarse";
    $retorno['respuesta'] = false;
}

if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);
