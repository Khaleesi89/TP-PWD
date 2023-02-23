<?php
require_once('../../../config.php');
$objSession = new SessionController();
$objMenuCon = new MenuController();
try {
    $rol = $objSession->getRolPrimo();
    if ($rol != '') {
        if ($rol == 'Admin') {
            $lista = $objMenuCon->listarTodo();
        } elseif ($rol == 'Cliente' || $rol == 'Deposito') {
            $lista = [];
        }
    }
} catch (\Throwable $th) {
    $rol = '';
    $lista = []; //  ['idproducto' => '', 'pronombre' => '', 'sinopsis'=>'', 'procantstock'=>'', 'autor'=>'', 'precio'=>'', 'isbn'=>'', 'categoria'=>''];
}

$arreglo_salid = array();
foreach ($lista as $key => $value) {
    $nuevoElemen = $value->dameDatos();
    array_push($arreglo_salid, $nuevoElemen);
}

echo json_encode($arreglo_salid);