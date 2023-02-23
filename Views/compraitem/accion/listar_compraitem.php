<?php

require_once('../../../config.php');
$objSession = new SessionController();
$idusuario = $objSession->getIdusuario();
$arreglo_salid = [];
$arrayBusc['idusuario'] = $idusuario;
//OBTENCION DEL TOTAL DE LAS COMPRAS
$objCompraCon = new CompraController();
$arraycompras = $objCompraCon->buscarCompraSConIdusuario($idusuario);
//LOS ID DE LAS todas COMPRAS del usuario
$arrayconLosId = $objCompraCon->soloId($arraycompras);
//BUSCAMOS CON COMPRAESTADO PARA QUE SE MUESTRE SOLO LAS INICIADAS
$objCompraestadoCon = new CompraestadoController();
//VAN LAS COMPRAS QUE TIENEN FECHAFIN = NULL
$sacandolsComprasActivas = $objCompraestadoCon->sacandoComprasActivas($arrayconLosId);
$comprAct = $sacandolsComprasActivas['array'];
//todas las compras iniciadas
$sololasIniciadas = $objCompraestadoCon->soloLasIniciadas($comprAct);

//SACAR LAS ID DE LAS COMPRAS iniciadas
$arrayconLosIdcomprasActivas = $objCompraCon->soloId($sololasIniciadas);
//BUSCAMOS TODOS LOS COMPRAITEM Y LOS COMPARAMOS CON EL ARRAY DE LAS ID COMPRAS ACTIVAS
$objConCompraitem = new CompraitemController();
$arrayCompItem = $objConCompraitem->sacandoComprasIniciadas($arrayconLosIdcomprasActivas);
foreach ($arrayCompItem as $key => $value) {
    $nuevoElemen = $value->dameDatosOk();
    
    array_push($arreglo_salid, $nuevoElemen);
}
echo json_encode($arreglo_salid);