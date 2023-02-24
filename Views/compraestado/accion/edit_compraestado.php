<?php

require_once('../../../config.php');

$objConCompraestado = new CompraestadoController();
$data = data_submitted();//[idcompraestado] => 147[idcompra] =>[cefechaini] => [idcompraestadotipo] =>
$resultadito = $objConCompraestado->modificacion($data);
echo json_encode($resultadito);






