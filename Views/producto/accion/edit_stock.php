<?php
require_once('../../../config.php');
$objProCon = new ProductoController();
$objSession = new SessionController();

$obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
$obtenerURL = array_reverse($obtenerURL);
$url = explode("=",$obtenerURL[0]);
$idprod = $url[1];
$data = data_submitted();
$data['idproducto'] = $idprod;//la data tiene  las siguientes keys
//pronombre,sinopsis,procantstock,autor,precio,isbn,categoria,cicantidad,idproducto
$retorno = $objProCon->inicioCompra($data,$objSession);
echo json_encode($retorno);