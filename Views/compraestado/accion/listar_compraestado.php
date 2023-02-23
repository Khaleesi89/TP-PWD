<?php
require_once('../../../config.php');
$objSession = new SessionController();
$objCompraEstadoCon = new CompraestadoController();
try {
    $rol = $objSession->getRolPrimo();
    if ($rol != '') {
        if ($rol == 'Admin' || $rol == 'Deposito') {
            $arrBu = [];
            $list = $objCompraEstadoCon->listarTodo($arrBu);
            //DEPURACION DE LA LISTA PARA QUE MUESTRE SOLO LOS ESTADOS QUE ESTEN CON FECHA FIN NULL(ESTADOS VIGENTE)
            //Y LOS DE ESTADO 1(INICIADA) Y 2 (ACEPTADA). NO SE MOSTRARAN LAS ENVIADAS Y LAS CANCELADAS
            $nuevaList = $objCompraEstadoCon->depuracion($list);
            $lista = [];
            foreach ($nuevaList as $key => $value) {
                $datos = $value->dameDatos();
                array_push($lista, $datos);
            }
        } elseif ($rol == 'Cliente') {
            $arrBuUs['idusuario'] = $objSession->getIdusuario();
            $objCompraCon = new CompraController();
            $listCompraDeCliente = $objCompraCon->listarTodo($arrBuUs);
            $arrList = [];
            foreach ($listCompraDeCliente as $key => $value) {
                $arrBuCEcliente['idcompra'] = $value->getIdcompra();
                $arrBuCEcliente['cefechafin'] = NULL;
                $lis = $objCompraEstadoCon->listarTodo($arrBuCEcliente);
                array_push($arrList, $lis);
            }
            $lista = [];
            foreach ($arrList as $key => $value) {
                foreach ($value as $llave => $valor) {
                    $datos = $valor->dameDatos();
                    $arDatos = ['idcompraestado' => $datos['idcompraestado'], 'idcompra' => $datos['idcompra'], 'idcompraestadotipo' => $datos['idcompraestadotipo'], 'cetdescripcion' => $datos['cetdescripcion'], 'cefechaini' => $datos['cefechaini'], 'cefechafin' => $datos['cefechafin']];
                    array_push($lista, $arDatos);
                }
            }
            
        }
        

    }
} catch (\Throwable $th) {
    $rol = '';
    $lista = []; //  ['idproducto' => '', 'pronombre' => '', 'sinopsis'=>'', 'procantstock'=>'', 'autor'=>'', 'precio'=>'', 'isbn'=>'', 'categoria'=>''];
}

echo json_encode($lista);