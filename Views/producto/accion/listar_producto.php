<?php
require_once('../../../config.php');

$objConPro = new ProductoController();
$objSession = new SessionController();
$rol = 'Admin';
if($rol != ''){
    if($rol == 'Admin' || $rol == 'Deposito'){
        $array = [];
        $lista = $objConPro->listarTodo($array);
    } elseif ($rol == 'Cliente') {
        $arrBuPro['prdeshabilitado'] = NULL;
        $lista = $objConPro->listarTodo($arrBuPro);
    }
    $arreglo_salid = array();
    foreach ($lista as $key => $value) {
        if ($value->getPrdeshabilitado() == NULL) {
            $nuevoElemen = $value->dameDatos();
            array_push($arreglo_salid, $nuevoElemen);
        }
    }
    echo json_encode($arreglo_salid);
} else {
    header($PRINCIPAL);
}