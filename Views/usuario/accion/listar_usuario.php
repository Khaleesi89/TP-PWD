<?php
/* require_once('../templates/header2.php'); */
require_once( '../../../config.php' );
$objUsuarioController = new UsuarioController();
$objUsuarioRolController = new UsuarioRolController();
$objSession = new SessionController();

$arrayRoles = $objUsuarioRolController->getRoles();
try {
    $rol = $objSession->getRolPrimo();
    if($rol != ''){
        if($rol == 'Admin'){
            $array = [];
            $lista = $objUsuarioController->listarTodo($array);
        }elseif($rol == 'Cliente' || $rol == 'Deposito'){
            //$arrBuPro['usdeshabilitado'] = NULL;
            $idusuario = $objSession->getIdusuario();
            $arrBuPro['idusuario'] = $idusuario;
            $lista = $objUsuarioController->listarTodo($arrBuPro);
        }
    }else{
        $lista = [];
    }
} catch (\Throwable $th) {
    $lista = [];
}
$arreglo_salid = array();
if(is_array($lista) && count($lista) > 0){
    foreach ($lista as $key => $value) {
        $nuevoElemen = $value->dameDatos();
        array_push($arreglo_salid, $nuevoElemen);
    }
}

echo json_encode($arreglo_salid);