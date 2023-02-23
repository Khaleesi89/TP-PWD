<?php

require_once('../../../config.php');
$objSession = new SessionController();
$objConCompra = new CompraController();
$usuario = $objSession->getIdusuario();
$usuariorol = $objSession->obtenerRol();
$usuariorol = $usuariorol[0];
$rol = $usuariorol->getObjRol()->getRodescripcion();
if($rol != ''){
    if($rol == 'Admin' || $rol == 'Deposito'){
        $array = [];
        $lista = $objConCompra->listarTodo($array);
    } elseif ($rol == 'Cliente') {
        $arrBuPro['idusuario'] = $objSession->getIdusuario();
        $lista = $objConCompra->listarTodo($arrBuPro);
    }
    $arreglo_salid = array();
    foreach ($lista as $key => $value) {
            $nuevoElemen = $value->dameDatos();
            array_push($arreglo_salid, $nuevoElemen);
        }

    echo json_encode($arreglo_salid);
} else {
    header($PRINCIPAL);
}
