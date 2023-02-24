<?php

use React\Promise\Promise;

class CompraitemController extends MasterController
{
    use Errores;

    public function listarTodo($arrBus){
        $arrayTotal = Compraitem::listar($arrBus);
        if(array_key_exists('array', $arrayTotal)){
            $array = $arrayTotal['array'];
        }else{
            $array = [];
        }
        
        return $array;    
    }

    public function listarTodos( $param ) {
        if( $param = null ) {
            $arrayBus['idcompraitem'] = NULL;
            $arrayTotal = Compraitem::listar( $arrayBus );
        } else {
            $arrayBus = $param;
            $arrayTotal = Compraitem::listar( $arrayBus );
        }

        if (array_key_exists('array', $arrayTotal)) {
            $array = $arrayTotal['array'];
        } else {
            $array = [];
        }
        return $array;
    }




    //buscar compraitem 
    public function buscar($data){
        $compraitem = new Compraitem();
        $rta = $compraitem->buscar($data);
        if($rta['respuesta']){
            $rta = $compraitem;
        }else{
            $rta = false;
        }
        return $rta;
        
    
    }

    public function cargarVentaDeProducto($idcompra, $idproducto, $cicantidad)
    {
        $objCompraItem = new CompraItem();
        //obtener producto
        $objProducto = new Producto();
        $arrPr['idproducto'] = $idproducto;
        $objProducto->buscar($arrPr);
        //obtener compra
        $objCompra = new Compra();
        $arrCr['idcompra'] = $idcompra;
        $objCompra->buscar($arrCr);
        $objCompraItem->cargar($objProducto, $objCompra, $cicantidad);
        $rt = $objCompraItem->insertar();
        if ($rt['respuesta']) {
            $response = true;
        } else {
            $response = false;
        }
        return $response;
    }

     //** Al comprar un producto se sumará la cantidad en el carrito 
    //o creara una tupla nueva                                                          

    public function unirMismoProducto($idcompra, $idproducto, $cicantidad){
    
        $bandera = false;
        $arrayBusqueda = ['idcompra' => $idcompra, 'idproducto' => $idproducto];
        $objCompraItem = new Compraitem();
        $busquedaCompleta = $objCompraItem->buscar($arrayBusqueda);
        //para un mismo producto
        if ($busquedaCompleta['respuesta']) {
            $cicantidadActual = $objCompraItem->getCicantidad();
            $cicantidadTotal = $cicantidadActual + $cicantidad;
            $objCompraItem->setCicantidad($cicantidadTotal);
            $resp = $objCompraItem->modificar();
            if($resp['respuesta']){
                $bandera = true;
            }
        }else{
            //si es un producto diferente
            $prod = new Producto();
            $buscar = ['idproducto' => $idproducto];
            $producto = $prod->buscar($buscar);
            $comp = new Compra();
            $compra = $comp->buscar($idcompra);
            $objCompraItem->cargar($producto,$compra,$cicantidad);
            $resp = $objCompraItem->insertar();
            $bandera = true; 
        }
        return $bandera;
    }

    
    //sacamos las tuplas de compraitems que estan en estado iniciada

    public function sacandoComprasIniciadas($arrayconLosIdcomprasActivas){
        $listaSalida = [];
        $compraitem = new Compraitem();
        $buscando = [];
        $listado = $compraitem->listar($buscando);
        $arrayCompraItem = $listado['array'];
        for ($i=0; $i < count($arrayCompraItem) ; $i++) { 
            $item = $arrayCompraItem[$i];
            $idCompra = $item->getObjCompra()->getIdcompra();
            if(in_array($idCompra,$arrayconLosIdcomprasActivas)){
                array_push($listaSalida,$item);
            }      
        }
        return $listaSalida;
    
    }

    //funcion para modificar la cantdiad de stock en compra item
    public function modificarCantidad($data){
        $objCompraItem = new Compraitem();
        $arrayBusc['idcompraitem'] = $data['idcompraitem'];
        $cantidad = $data['cicantidad'];
        $rtaS = $objCompraItem->buscar($arrayBusc);//objeto original
        
        if ($rtaS != null) {
            //FUNCION EN CONTROLADOR PAR AQUE TRAIGA LA CANTIDAD DE PRODUCTO
            //FUNCION PARA COMPRAR 
            $cantTotal = $objCompraItem->stockTotal($data['idproducto']);
            if ($cantTotal >= $cantidad) {
                $objCompraItem->setCicantidad($cantidad);
                $rta = $objCompraItem->modificar();
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
        return $retorno;
    }


    //FUNCION PARA LISTAR LOS PRODUCTOS DEL CARRITO
    public function sacarCarrito($idusuario){
        $arreglo_salid = [];
        //OBTENCION DEL TOTAL DE LAS COMPRAS
        $objCompra = new Compra();
        $arraycompras = $objCompra->buscarCompraSConIdusuario($idusuario);
        //LOS ID DE LAS todas COMPRAS del usuario
        $arrayconLosId = $objCompra->soloId($arraycompras);
        //BUSCAMOS CON COMPRAESTADO PARA QUE SE MUESTRE SOLO LAS INICIADAS
        $objCompraestado = new Compraestado();
        //VAN LAS COMPRAS QUE TIENEN FECHAFIN = NULL
        $sacandolsComprasActivas = $objCompraestado->sacandoComprasActivas($arrayconLosId);
        $comprAct = $sacandolsComprasActivas['array'];
        //todas las compras iniciadas
        $sololasIniciadas = $objCompraestado->soloLasIniciadas($comprAct);

        //SACAR LAS ID DE LAS COMPRAS iniciadas
        $arrayconLosIdcomprasActivas = $objCompra->soloId($sololasIniciadas);
        //BUSCAMOS TODOS LOS COMPRAITEM Y LOS COMPARAMOS CON EL ARRAY DE LAS ID COMPRAS ACTIVAS
        $objConCompraitem = new CompraitemController();
        $arrayCompItem = $objConCompraitem->sacandoComprasIniciadas($arrayconLosIdcomprasActivas);
        foreach ($arrayCompItem as $key => $value) {
            $nuevoElemen = $value->dameDatosOk();
            array_push($arreglo_salid, $nuevoElemen);
        }
        return $arreglo_salid;
    }  
}
