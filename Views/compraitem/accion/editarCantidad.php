<?php
require_once('../../../config.php');
$objConCompraItem = new CompraitemController();
$data = data_submitted();//[idcompraitem][idproducto][idcompra][cicantidad]
$arrayBusc['idcompraitem'] = $data['idcompraitem'];
$cantidad = $data['cicantidad'];
$rtaS = $objConCompraItem->buscar($arrayBusc);
if ($rtaS != null) {
    //FUNCION EN CONTROLADOR PAR AQUE TRAIGA LA CANTIDAD DE PRODUCTO
    //FUNCION PARA COMPRAR 
    $cantTotal = $objConCompraItem->stockTotal($data['idproducto']);
    if ($cantTotal >= $cantidad) {
        $rta = $objConCompraItem->modificar();
        if ($rta) {
            $mensaje = "Se modificó su cantidad de productos";
            $rta = true;
        }else{
            $mensaje = "No se modificó la cantidad de productos";
            $rta = false;
        }
    } else {
        $mensaje = 'No hay en stock esa cantidad';
        $rta = false;
    }
}

$retorno['respuesta'] = $rta;
if (isset($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);
