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


   

    //ACA EN MODIFICAR SETEAMOS LA CANTIDAD QUE QUEDA EN STOCK (DENTRO DE PRODUCTO, NO EN COMPRAITEM)
    public function modificar()
    {
        $rta = $this->buscarId();
        $response = false;
        if ($rta['respuesta']) {
            //puedo modificar con los valores
            $valores = $this->busqueda();
            $objCompraItem = $rta['obj'];
            $idproducto['idproducto'] = $valores['idproducto'];
            $idcompra['idcompra'] = $valores['idcompra'];
            $objProducto = new Producto();
            $objProducto->buscar($idproducto);
            $objCompra = new Compra();
            $objCompra->buscar($idcompra);
            $objCompraItem->cargar($objProducto, $objCompra, $valores['cicantidad']);
            $rsta = $objCompraItem->modificar();
            if ($rsta['respuesta']) {
                //todo gut
                $response = true;
            }
        } else {
            //no encontro el obj
            $response = false;
        }
        return $response;
    }



    public function buscarId()
    {
        $respuesta['respuesta'] = false;
        $respuesta['obj'] = null;
        $respuesta['error'] = '';
        $arrayBusqueda = [];
        $arrayBusqueda['idcompraitem'] =Data::buscarKey('idcompraitem');
        $objCompIt = new Compraitem();
        $rta = $objCompIt->buscar($arrayBusqueda);
        if ($rta['respuesta']) {
            $respuesta['respuesta'] = true;
            $respuesta['obj'] = $objCompIt;
        } else {
            $respuesta['error'] = $rta;
        }
        return $respuesta;
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
    public function busqueda()
    {
        $arrayBusqueda = [];
        $idcompraitem = Data::buscarKey('idcompraitem');
        $idproducto = Data::buscarKey('idproducto');
        $idcompra = Data::buscarKey('idcompra');
        $cicantidad = Data::buscarKey('cicantidad');

        $arrayBusqueda = [
            'idcompraitem' => $idcompraitem,
            'idproducto' => $idproducto,
            'idcompra' => $idcompra,
            'cicantidad' => $cicantidad,
        ];
        return $arrayBusqueda;
    }

    public function eliminar()
    {
        $rta = $this->buscarId();
        $response = false;
        if ($rta['respuesta']) {
            $objCompraItem = $rta['obj'];
            $respEliminar = $objCompraItem->eliminar();
            if ($respEliminar['respuesta']) {
                $response = true;
            }
        } else {
            //no encontro el obj
            $response = false;
        }
        return $response;
    }

    public function stockTotal($idproducto)
    {
        $producto['idproducto'] = $idproducto;
        $objetoProducto = new Producto();
        $busquedaProducto = $objetoProducto->buscar($producto);
        if ($busquedaProducto) {
            $cantStock = $objetoProducto->getProCantStock();
        }
        return $cantStock;
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

    
}
