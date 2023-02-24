<?php
class ProductoController extends MasterController{
    use Errores;


    //buscar producto 
    public function buscar($data){
        $producto = new Producto();
        $rta = $producto->buscar($data);
        if($rta['respuesta']){
            $rta = $producto;
        }else{
            $rta = false;
        }
        return $rta;
    }

    public function listarTodo($array){
        if(empty($array)){
            $arrayBusqueda = [];
            $arrayTotal = Producto::listar($arrayBusqueda);
            if(array_key_exists('array', $arrayTotal)){
                $array = $arrayTotal['array'];
            }else{
                $array = [];
            }
        }else{
            $arrayTotal = Producto::listar($array);
            if(array_key_exists('array', $arrayTotal)){
                $array = $arrayTotal['array'];
            }else{
                $array = [];
            }
        }
        return $array;        
    }


    public function insertar($data){
        $objCompra = new Producto();
        $objCompra->setProNombre( $data['pronombre'] );
        $objCompra->setSinopsis( $data['sinopsis'] );
        $objCompra->setProCantStock( $data['procantstock'] );
        $objCompra->setAutor( $data['autor'] );
        $objCompra->setPrecio( $data['precio'] );
        $objCompra->setIsbn( $data['isbn'] );
        $objCompra->setCategoria( $data['categoria'] );
        $foto = $this->getSlashesImg();
        $objCompra->setFoto($foto);
        $rta = $objCompra->insertar();
        return $rta;
    } 


    public function modificar($data){
        $objProducto = new Producto();
        $array['idproducto'] = $data['idproducto'];
        $rta = $objProducto->buscar($array);
        if($rta['respuesta']){
            $objProducto->setProNombre($data['pronombre']);
            $objProducto->setSinopsis($data['sinopsis']);
            $objProducto->setProCantStock($data['procantstock']);
            $objProducto->setAutor($data['autor']);
            $objProducto->setPrecio($data['precio']);
            $objProducto->setIsbn($data['isbn']);
            $objProducto->setCategoria($data['categoria']);
            $rsta = $objProducto->modificar();
            if($rsta['respuesta']){
                //todo gut
                $response = true;
            }else{
                $response = false;
            }
        }else{
            //no encontro el obj
            $response = false;
        }
        return $response;
    }

    

    public function obtenerStockPorId($idproducto){
        $arrBus = [];
        $arrBus['idproducto'] = $idproducto;
        $objProducto = new Producto();
        $rta = $objProducto->buscar($arrBus);
        if($rta['respuesta']){
            $respuesta = $objProducto->getProCantStock();
        }else{
            $respuesta = false;
        }
        return $respuesta;
    }


    //FUNCION EN EL PROCESO DE COMPRA

    public function inicioCompra($data,$objSession){
        $cicantidad = $data['cicantidad'];
        $cantStock = $data['procantstock'];
        $idprod = $data['idproducto'];
        //Comprobar stock
        if ($cicantidad <= $cantStock) {
            //buscar si hay una compra iniciada
            $idusuario = $objSession->getIdusuario();//obtengo id usuario
            //obtener compra con idusuario
            $objCompra = new Compra();
            $ComprUsuario = $objCompra->buscarCompraSConIdusuario($idusuario);
            //true si esta vacio y false si no
            if(!empty($ComprUsuario)){
                //ENTRA ACA SI TIENE COMPRAS iniciadas o finalizadas
                //obtener solo los id
                $arrayconLosId = $objCompra->soloId($ComprUsuario);
                $objCompraestado = new Compraestado();
                //VAN LAS COMPRAS QUE TIENEN FECHAFIN = NULL
                $sacandolsComprasActivas = $objCompraestado->sacandoComprasActivas($arrayconLosId);
                //ESTO ES TRUE -> HAY COMPRAS DEL USUARIO ANTERIORMENTE
                if($sacandolsComprasActivas['respuesta']){
                    $comprAct = $sacandolsComprasActivas['array'];
                    //AHORA TENGO QUE SACAR LAS QUE TIENEN SOLO COMPRAESTADO1 pero SOLO DEVUELVE 1
                    //devuelve el id de la compra en estado 1 y es true si no hay compras activas
                    $unaCompraestadoinicial = $objCompraestado->soloEstadoInicial($comprAct);
                    if($unaCompraestadoinicial){
                        //COMPRA CON ID EN ESTADO INICIADA ENTONCES SOLO HACE EL COMPRAITEM
                        
                        $resp = $objCompra->compraConCompraIniciada($unaCompraestadoinicial,$idusuario,$idprod,$cicantidad);
                        if($resp){
                            $mensaje = "su compra se realizó correctamente";
                            $respuesta = true;
                        }else{
                            $mensaje = "Hay un error en su compra";
                            $respuesta = false;
                        }
                        $retorno = [];
                        $retorno['respuesta'] = $respuesta;
                        if (isset($mensaje)) {
                            $retorno['errorMsg'] = $mensaje;
                        }
                    }else{ 
                        //SIN COMPRA EN ESTADO INICIADA ENTONCES CREA UNA NUEVA COMPRA
                        $resp = $objCompra->crearCompra($idusuario,$idprod,$cicantidad);
                        if($resp){
                            $mensaje = "su compra se realizó correctamente";
                            $respuesta = true;
                        }else{
                            $mensaje = "Hay un error en su compra";
                            $respuesta = false;
                        }
                        $retorno = [];
                        $retorno['respuesta'] = $respuesta;
                        if (isset($mensaje)) {
                            $retorno['errorMsg'] = $mensaje;
                        }
                    }
                }
            }else{
                //si no hay compras para el usuario
                //LO QUE HARA SI no tenia compras anteriormente nuevo usuario
            
                $resp = $objCompra->crearCompra($idusuario,$idprod,$cicantidad);
                if($resp){
                    $mensaje = "su compra se realizó correctamente";
                    $respuesta = true;
                }else{
                    $mensaje = "Hay un error en su compra";
                    $respuesta = false;
                }
                $retorno = [];
                $retorno['respuesta'] = $respuesta;
                if (isset($mensaje)) {
                    $retorno['errorMsg'] = $mensaje;
                }
            }
        } else {
            $mensaje = "El stock de compra ingresado es superior al de depósito";
            $respuesta = false;
        }
        $retorno = [];
        $retorno['respuesta'] = $respuesta;
        if (isset($mensaje)) {
            $retorno['errorMsg'] = $mensaje;
        }
        return $retorno;
    }


}