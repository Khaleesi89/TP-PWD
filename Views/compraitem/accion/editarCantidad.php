<?php
require_once('../../../config.php');
$objConCompraItem = new CompraitemController();
$data = data_submitted();//[idcompraitem][idproducto][idcompra][cicantidad]
$rtaS = $objConCompraItem->modificarCantidad($data);
echo json_encode($rtaS);
