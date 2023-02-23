<?php
require_once('../../config.php');

$objUsuarioCon = new UsuarioController;
$gola = $objUsuarioCon->buscarObjUsuario2();


if($gola['rta']){
    $objSession = new SessionController();
    $valido = $objSession->validarCredenciales();
    if($valido){
        $url = $PRODUCTOS;
        
    }
} else {
    $url = $PRINCIPAL;
}
header($url);
?>