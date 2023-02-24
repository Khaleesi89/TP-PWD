<?php

require_once('../../../config.php');
$objCompraItemCon = new CompraitemController();
$objSession = new SessionController();
//SACAR EL ID USUARIO
$idusuario = $objSession->getIdusuario();
$arreglo_salid = [];
$arrayBusc['idusuario'] = $idusuario;
$rol = $objSession->getRolPrimo();
$list = [];
if($rol == 'Admin' || $rol == 'Deposito'){
    $arrBuCI = [];
    $list = $objCompraItemCon->listarTodo($arrBuCI);  
}else{
    //averiguar la compra que tiene...solo una sola compra activa se permite
    $comprasTotales = $objCompraItemCon->sacarCarrito($idusuario);
}        
echo json_encode($comprasTotales);
