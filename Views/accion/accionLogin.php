<?php
require_once('../../config.php');

$objUsuarioCon = new UsuarioController;
$data = data_submitted();
$gola = $objUsuarioCon->buscarUsuario($data);
if($gola){
    $objSession = new SessionController();
    $valido = $objSession->validarCredenciales($data);
    if($valido){
        $url = $PRODUCTOS;
        
    }
} else {
    $url = $PRINCIPAL;
}
header($url);
?>